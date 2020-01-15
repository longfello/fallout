<?php
$this->breadcrumbs=array(
	'Ошибки'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Журнал','url'=>array('index')),
	array('label'=>'Удалить','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить запись о этой ошибке?')),
);
?>

<h1>Просмотр ошибки #<?php echo $model->id; ?></h1>

<table id="yw0" class="detail-view table table-striped table-condensed">
	<tbody>
		<tr class="odd"><th>Уровень</th><td><?= $model->level ?></td></tr>
		<tr class="even"><th>Категория</th><td><?= $model->category ?></td></tr>
		<tr class="odd"><th>Дата, время</th><td><?= date('d.m.Y H:i:s', $model->logtime) ?></td></tr>
		<tr class="even"><th>Сообщение</th><td><pre> <?= CHtml::encode($model->message) ?> </pre></td></tr>
	</tbody>
</table>
