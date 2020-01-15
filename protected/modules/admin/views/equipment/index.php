<?php
/**
 * @var $model Equipment
 */
$this->breadcrumbs=array(
	'Инвентарь'=>array('index'),
	'Управление',
);

$this->menu=array(
array('label'=>'Добавить','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('equipment-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Управление оборудованием</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'equipment-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'owner',
		'clan' => array(
			'name'        => 'clan',
			'filter'      => CHtml::dropDownList( 'Equipment[clan]', $model->clan,
				[null => '-', '0' => '-- Общедоступный --']+CHtml::listData( Clans::model()->findAll( array( 'order' => 'name' ) ), 'id', 'name' )
			)
		),
		'name',
		'status' => array(
			'name'        => 'status',
			'filter'      => CHtml::dropDownList( 'Equipment[status]', $model->status,
				[null => '-'] + $model->statusAvailable
			),
		),
		'type' => array(
			'name'        => 'type',
			'filter'      => CHtml::dropDownList( 'Equipment[type]', $model->type,
		  [null => '-'] + $model->typesAvailable
			),
			'value' => '$data->getTypeName()'
		),
		'class' => array(
			'name'        => 'class',
			'filter'      => CHtml::dropDownList( 'Equipment[class]', $model->class,
				[null => '-'] + $model->classAvaliable
			),
		),
		'cost',
		'mtype' => array(
			'name'        => 'mtype',
			'filter'      => CHtml::dropDownList( 'Equipment[mtype]', $model->mtype,
				[null => '-'] + $model->mtypeAvaliable
			),
			'value' => '$data->getMTypeName()'
		),
		'location_use',
		/*
		'minlev',
		'opis',
		'shoplvl',
		'eprot',
		'uname',
		'weight',
		'product',
		'min_strength',
		'min_agility',
		'min_defense',
		'min_max_energy',
		'min_max_hp',
		'add_strength',
		'add_agility',
		'add_defense',
		'add_max_energy',
		'add_max_hp',
		'add_hp',
		'add_energy',
		'add_pohod',
		'time_effect',
		'durability',
		'no_weapon',
		'toxic',
		'is_caves_drop',
		'post_time_effect',
		'post_strength',
		'post_agility',
		'post_defense',
		'post_max_energy',
		'post_max_hp',
		'post_hp',
		'post_energy',
		'post_pohod',
		'location_use',
		'className',
		'params',
		*/
array(
'class'=>'booster.widgets.TbButtonColumn',
'template' => '{update}&nbsp;{delete}',
),
),
)); ?>
