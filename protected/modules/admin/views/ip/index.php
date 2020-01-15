<?php
$this->breadcrumbs=array(
	'Защита админки по ip'=>array('index'),
	'Управление',
);

$this->menu=array(
array('label'=>'Добавить IP-адрес','url'=>array('create')),
);
?>
<h1>Управление разрешенными IP адресами</h1>

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'admins-ip-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
'ip',
'comment',
array(
'class'=>'booster.widgets.TbButtonColumn',
),
),
)); ?>
