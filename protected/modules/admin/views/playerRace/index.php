<?php
$this->breadcrumbs=array(
	'Расы персонажей'=>array('index'),
);

$this->menu=array(
	array('label'=>'Добавить расу','url'=>array('create')),
	array('label'=>'Преимущества рас','url'=>array('/admin/playerRaceBenefitList/index')),
);
?>
<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'player-race-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}'
		),
	),
)); ?>
