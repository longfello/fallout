<?php
$this->breadcrumbs=array(
	'Раса' => array('/admin/playerRace/update', 'id'=>$model->race_id),
	'Внешний вид расы'=>array('index', array('race_id' => $model->race_id)),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Раса','url'=>array('/admin/playerRace/update', 'id'=>$model->race_id)),
	array('label' => 'Внешний вид расы', 'url'=>array('index', 'race_id' => $model->race_id)),
	array('label'=>'Добавить слой','url'=>array('create', 'race_id' => $model->race_id)),
);
?>

<h1>Добавить слой внешнего вида расы</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>