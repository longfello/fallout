<?php
$clan = Clans::model()->findByPk($model->clan);

$this->breadcrumbs = array(
	'Кланы' => array('/admin/clans/index'),
	$clan->name => array('/admin/clans/update', 'id' => $clan->id),
	'Журнал кладовки',
);

$this->menu = array(
	array( 'label' => 'Клан', 'url' => array( '/admin/clans/update', 'id' => $clan->id ) ),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#cstore-history-grid').yiiGridView('update', {
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

	<?php echo CHtml::hiddenField('clan_id' , $clan->id); ?>
	<div class="row">
		<div class="col-sm-6">
			<?php echo $form->dropDownListGroup($model,'operation_filter', array('widgetOptions'=>array('data'=>array(''=>'Любая',
				'put'=>'Добавление',
				'give'=>'Выдача',
				'gift'=>'Добавление (админ)',
				'remove'=>'Изьятие (админ)',
			), 'htmlOptions'=>array()))); ?>
		</div>
		<div class="col-sm-6">
			<?php echo $form->dropDownListGroup($model,'item_filter', array('widgetOptions'=>array('data'=>array(''=>'Любой',
				'item'=>'Итем',
				'gold'=>'Золото',
				'platinum'=>'Крышки',
			), 'htmlOptions'=>array()))); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<?php echo $form->datePickerGroup($model, 'dt_start',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy')),'prepend'=>'<i class="fa fa-calendar"></i>')); ?>
		</div>
		<div class="col-sm-6">
			<?php echo $form->datePickerGroup($model, 'dt_end',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy')),'prepend'=>'<i class="fa fa-calendar"></i>')); ?>
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
	</div>

<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'cstore-history-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'dt' => [
			"name" => 'dt',
			"type" => 'raw',
			"value" => '"<span title=\"#{$data->id}\">".date("d.m.Y H:i:s", strtotime($data->dt))."</span>"',
		],
		'operation' => [
			"name"  => 'operation',
			"type"  => 'raw',
			"value" => '$data->getPlayerLink()'
		],
		'subject' => [
			"name"  => 'subject',
			"type"  => 'raw',
			"value" => '$data->getItemLink()'
		],
		array(
			'class' => 'booster.widgets.TbButtonColumn',
			'template' => '{delete}'
		),
	),
) ); ?>
