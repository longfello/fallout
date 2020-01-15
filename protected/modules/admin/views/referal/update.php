<?php
$this->breadcrumbs=array(
	'Реферальные ссылки' => array( 'index' ),
	$model->name=>array('update','id'=>$model->id),
	'Редактирование',
);

	$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id)),
	array('label'=>'Список','url'=>array('index')),
	);
	?>

	<h1>Редактирование ссылки <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>