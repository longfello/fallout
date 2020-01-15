<?php
$this->breadcrumbs=array(
	'Подарки'=>array('/admin/presents/index'),
	$present->name =>array('/admin/presents/update', 'id' => $present->id),
	'Детали',
);

$this->menu=array(
	array('label'=>'Подарить','url'=>array('create', 'present_id' => $present->id)),
);

?>
<img src="<?= $present->getImageUrl() ?>" style="max-height:64px;margin-right: 16px;" class="pull-left">
<h3>Подарок &laquo;<?= $present->name ?>&raquo; подарен следующим игрокам:</h3>
<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'player-presents-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'owner' => [
			'name'  => 'owner',
			'type'  => 'raw',
			'value' => '$data->owner?CHtml::link("{$data->ownerModel->user} [{$data->owner}]", array("/admin/players/update", "id" => $data->owner)):""'
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
