<?php
$this->breadcrumbs=array(
	'Защита админки по ip'=>array('index'),
	'Редактирование IP-адреса',
);

	$this->menu=array(
	array('label'=>'Перечень IP-адресов','url'=>array('index')),
	array('label'=>'Добавить IP-адрес','url'=>array('create')),
	);
	?>

	<h1>Редактирование IP-адреса #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>