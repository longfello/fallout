<?php
$this->breadcrumbs=array(
	'Расы'=>array('/admin/playerRace/index'),
	'Преимущества',
);

$this->menu=array(
	array('label'=>'Расы','url'=>array('/admin/playerRace/index')),
	array('label'=>'Добавить преимущество','url'=>array('create')),
);
?>
<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'player-race-benefit-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}'
		),
	),
)); ?>
