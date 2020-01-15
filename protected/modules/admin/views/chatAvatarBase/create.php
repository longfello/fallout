<?php
$this->breadcrumbs=array(
    'Аватарки в чате'=>array('index'),
	'Добавление',
);

$this->menu=array(
    array('label'=>'Список аватарок','url'=>array('index')),
);
?>

<h1>Добавить аватарку</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>