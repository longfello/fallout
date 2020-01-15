<?php
$this->breadcrumbs=array(
	'Игрок #'.$player_id => array('/admin/players/update', 'id' => $player_id),
	'Логи',
);

$this->menu=array(
	array('label'=>'Игрок','url'=>array('/admin/players/update', 'id' => $player_id)),
);
?>

<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'dt',
		'unread' => [
			'name'  => 'unread',
			'value' => '($data->unread == "F")?"Новое":"Прочтено"',
			'filter' => CHtml::dropDownList( 'Log[unread]', $model->unread,
				[null => ' - ', 'F' => 'Новое', 'T' => 'Прочтено']
			)
		],
		'CategoryId' => array(
			'name'  => 'CategoryId',
			'value' => '$data->category->name',
			'filter' => CHtml::dropDownList( 'Log[CategoryId]', $model->CategoryId,
				[null => ' - '] + CHtml::listData(LogCategories::model()->findAll(['order' => 'ord']),'id', 'name')
			)
		),
		'text' => array(
			'type'        => 'raw',
			'name'        => 'log',
			'value'       => 'logdata::render($data->attributes)',
		),
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{delete}'
		),
	),
)); ?>
