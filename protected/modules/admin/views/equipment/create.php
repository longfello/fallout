<?php
$this->breadcrumbs=array(
	'Инвентарь'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Перечень','url'=>array('index')),
);
?>

<h1>Добавить инвентарь</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>