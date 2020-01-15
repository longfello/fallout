<?php
$this->breadcrumbs=array(
	'Квесты'=>array('index'),
	'Создание',
);

$this->menu=array(
    array('label'=>'Перечень','url'=>array('index')),
);
?>

<h1>Создание квеста</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>