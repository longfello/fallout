<?php
$this->breadcrumbs=array(
	'Мобы'=>array('index'),
	$model->name,
	'Редактирование',
);

	$this->menu=array(
		array('label'=>'Добавить','url'=>array('create')),
		array('label'=>'Управление','url'=>array('admin')),
	);
	?>

	<h1>Редактирование моба #<?php echo $model->id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>