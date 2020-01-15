<?php
$this->breadcrumbs=array(
	'Реферальные ссылки' => array( 'index' ),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('index')),
);
?>

<h1>Добавление реферальной ссылки</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>