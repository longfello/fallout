<?php
$this->breadcrumbs=array(
	'Татуировки'=>array('index'),
	'Добавление',
);

$this->menu=array(
array('label'=>'Список','url'=>array('index')),
);
?>

<h1>Добавить татуировку</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>