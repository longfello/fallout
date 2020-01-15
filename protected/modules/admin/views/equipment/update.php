<?php
$this->breadcrumbs=array(
	'Инвентарь'=>array('index'),
	$model->name=>array('update','id'=>$model->id),
	'Редактирование',
);

	$this->menu=array(
	array('label'=>'Перечень','url'=>array('index')),
	array('label'=>'Добавить','url'=>array('create')),
	);
	?>

	<h1>Редактирование #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>