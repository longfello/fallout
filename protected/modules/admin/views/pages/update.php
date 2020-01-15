<?php
$this->breadcrumbs=array(
	'Статические страницы'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактирование',
);

	$this->menu=array(
	array('label'=>'Список','url'=>array('index')),
	array('label'=>'Создать','url'=>array('create')),
	);
	?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>