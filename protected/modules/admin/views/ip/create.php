<?php
$this->breadcrumbs=array(
	'Защита админки по ip'=>array('index'),
	'Добавить IP',
);

$this->menu=array(
array('label'=>'Перечень IP-адресов','url'=>array('index')),
);
?>

<h1>Добавить IP-адрес</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>