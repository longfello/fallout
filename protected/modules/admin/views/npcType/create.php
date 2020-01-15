<?php
$this->breadcrumbs=array(
	'Мобы'=>array('/admin/npc/index'),
	'Типы мобов'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Мобы','url'=>array('/admin/npc/index')),
	array('label'=>'Добавить','url'=>array('create')),
	array('label'=>'Управление типами','url'=>array('index')),
);
?>

<h1>Добавить тип мобов</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>