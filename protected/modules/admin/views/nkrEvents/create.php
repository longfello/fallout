<?php
$this->breadcrumbs=array(
	'Nkr Events'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список ивентов','url'=>array('index')),
);
?>

<h1>Cоздание ивента</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>