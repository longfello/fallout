<?php
$this->breadcrumbs=array(
	'robots.txt'=>array('index'),
	'Добавить',
);

$this->menu=array(
array('label'=>'Управление','url'=>array('admin')),
);
?>

<h1>Добавить файл robots.txt</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>