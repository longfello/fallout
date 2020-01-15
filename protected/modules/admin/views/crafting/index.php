<?php
$this->breadcrumbs=array(
	'Крафтинг'=>array('index'),
	'Управление',
);

$this->menu=array(
  array('label'=>'Добавить крафтинг','url'=>array('create')),
);
?>

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'crafting-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'name',
		'type' => [
		  'name' => 'type',
		  'value' => 'isset($data->availableProfessions[$data->type])?$data->availableProfessions[$data->type]:"?"',
		  'filter' => CHtml::dropDownList('Crafting[type]', $model->type, [null => '-'] + $model->availableProfessions)
		],
		'minlev',
		'maxlev',
	/*
	  'minpro',
	  'maxpro',
	  'energy',
	  'chance',
	  'chancepp',
	  'toprand',
	  'success_item',
	  'success_item_count',
	  'fail_text',
	  'fail_item',
	  'fail_item_count',
	  'max_exp_prolvl',
	  */
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update} {delete}'
		),
	),
)); ?>
