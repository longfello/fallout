<?php
$this->breadcrumbs=array(
	'Рецепты'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список рецептов','url'=>array('index')),
);
?>

<h1>Создать рецепт</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>