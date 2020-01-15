<?php
	$this->breadcrumbs=array(
		'Расы персонажей'=>array('index'),
		'Добавить',
	);

	$this->menu=array(
		array('label'=>'Перечень','url'=>array('index')),
	);
?>

<h1>Добавление расы</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'benefits' => [])); ?>