<?php
$this->breadcrumbs=array(
	'Игроки'=>array('/admin/players/index'),
	$player->user =>array('/admin/players/update', 'id' => $player->id),
	'Подарки',
);

$this->menu=array(
	array('label'=>'Подарить','url'=>array('create', 'player_id' => $player->id)),
);

?>
<h3>Игроку &laquo;<?= $player->user ?>&raquo; подарены следующие подарки:</h3>
<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'player-presents-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'image' => [
			'header'  => 'Изображение',
			'type'  => 'raw',
			'filter' => false,
			'value' => '"<img src=\"".$data->presentModel->getImageUrl()."\" style=\"max-height:32px;\">"'
		],
		'present' => [
			'header'  => 'Название',
			'filter'  => false,
			'type'  => 'raw',
			'value' => '$data->presentModel->name'
		],
		'giver' => [
			'name'  => 'giver',
			'type'  => 'raw',
			'value' => '$data->giver?CHtml::link("{$data->giverModel->user} [{$data->giver}]", array("/admin/players/update", "id" => $data->giver)):""'
		],
		'message',
		'date',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}'
		),
	),
)); ?>
