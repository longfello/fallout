<?php
$this->breadcrumbs=array(
	'Мобы'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Управление мобами','url'=>array('index')),
);
?>

<h1>Добавить моба</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>