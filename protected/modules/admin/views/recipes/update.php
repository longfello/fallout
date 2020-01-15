<?php
$this->breadcrumbs=array(
	'Рецепты'=>array('index'),
	$model->recipe_id,
	'Обновить',
);

	$this->menu=array(
        array('label'=>'Список рецептов','url'=>array('index')),
		array('label'=>'Создать рецепт','url'=>array('create')),
	);
	?>

	<h1>Обновить рецепт #<?php echo $model->recipe_id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>