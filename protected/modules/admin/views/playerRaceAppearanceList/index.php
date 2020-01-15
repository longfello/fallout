<?php
$this->breadcrumbs=array(
	'Раса' => array('/admin/playerRace/update', 'id'=>$model->race_id),
	'Внешний вид расы'=>array('index', 'race_id' => $model->race_id),
	'Управление',
);

$this->menu=array(
	array('label'=>'Раса','url'=>array('/admin/playerRace/update', 'id'=>$model->race_id)),
	array('label'=>'Добавить слой','url'=>array('create', 'race_id' => $model->race_id)),
);
?>
<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'player-race-appearance-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'image' => [
			'name' => null,
			'filter' => null,
			'type' => 'raw',
			'value' => '"<img src=\"". $data->getPicture("layout", true) ."\" style=\"height:64px\">"',
		],
		'gender'  => [
			'name'        => 'gender',
			'filter'      => CHtml::dropDownList( 'PlayerRaceAppearanceList[gender]', $model->gender,
				[null => '-', 'male' => 'Мужской', 'female' => 'Женский']
			),
			'value'       => '($data->gender == "female")?"Женский":"Мужской"'
		],
		'appearance_layout_id' => [
			'name'        => 'appearance_layout_id',
			'filter'      => CHtml::dropDownList( 'PlayerRaceAppearanceList[appearance_layout_id]', $model->appearance_layout_id,
				[null => '-']+CHtml::listData( AppearanceLayout::model()->findAll( array( 'order' => 'sort_order' ) ), 'id', 'name')
			),
			'value'       => '$data->layout->name'
		],
		'default_layout' => [
			'name' => 'default_layout',
			'filter' => CHtml::dropDownList( 'PlayerRaceAppearanceList[default_layout]', $model->default_layout,
				[null => '-', '0' => 'Нет', '1' => 'Да']
			),
			'type' => 'raw',
			'value' => '$data->default_layout?"*":""',
		],
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}'
		),
	),
)); ?>
