<?php
$this->breadcrumbs=array(
	'Набитые татуировки'=>array('index'),
	'Набить',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index')),
);
?>

<h1>Набить татуировку</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>