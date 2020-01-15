<?php
$this->breadcrumbs=array(
	'Мобы'=>array('index'),
	'Управление',
);

$this->menu=array(
  array('label'=>'Добавить','url'=>array('create')),
  array('label'=>'Типы мобов','url'=>array('/admin/npcType/index')),
  array('label'=>'Массовый дроп','url'=>array('/admin/npc/drop')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('npc-grid', {
data: $(this).serialize()
});
return false;
});
");
?>
<div class="search-form">
    <?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
    )); ?>

	<div class="row">
		<div class="col-sm-6">
            <?php echo $form->numberFieldGroup($model, 'level_start'); ?>
		</div>
		<div class="col-sm-6">
            <?php echo $form->numberFieldGroup($model, 'level_end'); ?>
		</div>
	</div>

	<div class="form-actions">
        <?php $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'context'=>'primary',
            'label'=>'Поиск',
        )); ?>
	</div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'npc-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
  	'id',
  	'pic' => [
	    'name' => 'type',
	    'value' => 'CHtml::image($data->getImage(), $data->name, ["style"=>"max-height:75px;max-width:100px;"]);',
	    'filter' => false,
	    'type' => 'raw',
    ],
		'name',
		'type' => [
		'name' => 'type',
		'value' => '$data->typeModel->name',
		'filter' => CHtml::activeDropDownList($model, 'type', [null => ''] + CHtml::listData( NpcType::model()->findAll( array( 'order' => 'name' ) ), 'id', 'name')),
    ],
		'level',
  	'gold',
  	'platinum',
  	'typeloc' => [
  	  'name' => 'typeloc',
      'value' => '$data->getLocationName()',
      'filter' => CHtml::activeDropDownList($model, 'typeloc', [null => ''] + $model->locations),
    ],
	[
		'name' => 'enabled',
		'type'  => 'raw',
		'value' => '$data->enabledDropdown',
		'filter' => [
			0 => 'Запрещен',
			1 => 'Разрешен'
		]
	],

		/*
		'desc',
		'strength',
		'agility',
		'gender',
		'hp',
		'max_hp',
		'defense',
		'ap',
		*/
    array(
      'class'=>'booster.widgets.TbButtonColumn',
      'template' => '{update} {delete}'
    ),
  ),
));

$url = $this->createUrl('npc/update');
Yii::app()->clientScript->registerScript('initEnabled',"
	$(document).on('change','select.enabled-state',function() {
        el = $(this);
        var data = {
          Npc: {
            enabled: el.val(),
          },
          ".Yii::app()->request->csrfTokenName.":'".Yii::app()->request->csrfToken."'
        };
        $.post('{$url}?id='+el.data('id'), data, function(){
          $(el).css('color', 'green').animate({'color': '#000'}, 500);
        });
    });", CClientScript::POS_READY);