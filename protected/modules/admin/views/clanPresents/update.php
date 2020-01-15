<?php
$this->breadcrumbs=array(
    'Подарки кланам'=>array('index'),
	$model->name=>$model->id,
	'Редактирование',
);

	$this->menu=array(
		array('label'=>'Список подарков','url'=>array('index')),
        array('label'=>'Добавить подарок','url'=>array('create')),
	);
	?>

	<h1>Редактирование кланового подарка <?php echo $model->id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>