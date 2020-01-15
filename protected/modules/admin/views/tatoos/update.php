<?php
$this->breadcrumbs=array(
	'Татуировки'=>array('index'),
	$model->name,
	'Редактирование',
);

	$this->menu=array(
	array('label'=>'Список','url'=>array('index')),
	array('label'=>'Добавить','url'=>array('create')),
	);
	?>

	<h1>Редактирование #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>