<?php
$this->breadcrumbs=array(
	'Крафтинг'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редавтирование',
);

	$this->menu=array(
		array('label'=>'Добавить крафтинг','url'=>array('create')),
		array('label'=>'Управление','url'=>array('index')),
	);
	?>

	<h1>Редактирование крафтинга #<?php echo $model->id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>