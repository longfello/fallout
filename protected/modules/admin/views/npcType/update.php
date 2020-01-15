<?php
$this->breadcrumbs=array(
	'Мобы'=>array('/admin/npc/index'),
	'Типы мобов'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактировать',
);

	$this->menu=array(
	  array('label'=>'Мобы','url'=>array('/admin/npc/index')),
		array('label'=>'Добавить','url'=>array('create')),
		array('label'=>'Управление типами','url'=>array('index')),
	);
	?>

	<h1>Редактировать тип мобов #<?php echo $model->id; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>