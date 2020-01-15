<?php
$this->breadcrumbs=array(
	'Крафтинг'=>array('index'),
	'Добавить крафтинг',
);

$this->menu=array(
	array('label'=>'Управление','url'=>array('index')),
);
?>

<h1>Добавить крафтинг</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>