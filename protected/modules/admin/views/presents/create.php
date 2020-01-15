<?php
	$this->breadcrumbs=array(
		'Подарки'=>array('index'),
		$model->name=>array('update','id'=>$model->id),
		'Добавить',
	);

	$this->menu=array(
		array('label'=>'Перечень','url'=>array('index')),
		array('label'=>'Добавить','url'=>array('create')),
	);
	?>

	<h1>Добавить подарок</h1>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>