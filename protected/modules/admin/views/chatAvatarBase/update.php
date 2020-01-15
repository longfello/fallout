<?php
$this->breadcrumbs=array(
    'Аватарки в чате'=>array('index'),
	$model->avatar_id,
	'Редактирование',
);

	$this->menu=array(
		array('label'=>'Список аватарок','url'=>array('index')),
        array('label'=>'Добавить аватарку','url'=>array('create')),
	);
	?>

	<h1>Редактирование аватарки <?php echo $model->avatar_id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>