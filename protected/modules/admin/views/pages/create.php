<?php
$this->breadcrumbs=array(
	'Статические страницы'=>array('index'),
	'Создать',
);

$this->menu=array(
array('label'=>'Список','url'=>array('index')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>