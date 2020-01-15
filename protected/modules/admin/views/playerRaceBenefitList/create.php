<?php
$this->breadcrumbs=array(
	'Расы'=>array('/admin/playerRace/index'),
	'Преимущества'=>array('index'),
	'Добавление',
);

$this->menu=array(
	array('label'=>'Расы','url'=>array('/admin/playerRace/index')),
	array('label'=>'Преимущества','url'=>array('index')),
	array('label'=>'Добавить преимущество','url'=>array('create')),
);
?>
<h1>Добавить преимущество</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>