<?php
$this->breadcrumbs=array(
	'Подарки'=>array('index'),
	$model->name=>array('update','id'=>$model->id),
	'Редактирование',
);

	$this->menu=array(
		array('label'=>'Перечень','url'=>array('index')),
		array('label'=>'Добавить','url'=>array('create')),
		array('label'=>'Подарить','url'=>array('/admin/playerPresents/create', 'present_id' => $model->id)),
		array('label'=>'Кому подарен','url'=>array('/admin/playerPresents/index', 'present_id' => $model->id)),
	);
	?>

	<h1>Редактировать подарок #<?php echo $model->id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>