<?php
$this->breadcrumbs=array(
	'Квесты'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Редактирование',
);

	$this->menu=array(
		array('label'=>'Добавить','url'=>array('create')),
		array('label'=>'Перечень','url'=>array('index')),
	);
	?>

	<h1>Редактирование квеста #<?php echo $model->id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>