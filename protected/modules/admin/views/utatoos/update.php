<?php
$this->breadcrumbs=array(
	'Набитые татуировки'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Редактирование',
);

	$this->menu=array(
		array('label'=>'Список','url'=>array('index')),
		array('label'=>'Добавить','url'=>array('create')),
	);
	?>

	<h1>Редактирование #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>