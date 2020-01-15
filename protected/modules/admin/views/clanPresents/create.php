<?php
$this->breadcrumbs=array(
    'Подарки кланам'=>array('index'),
	'Добавить',
);

$this->menu=array(
    array('label'=>'Список подарков','url'=>array('index')),
);
?>

<h1>Добавление кланового подарка</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>