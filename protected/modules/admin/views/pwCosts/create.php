<?php
$this->breadcrumbs=array(
	'Цены крышек'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список цен','url'=>array('index')),
);
?>

<h1>Создание цены крышек</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

