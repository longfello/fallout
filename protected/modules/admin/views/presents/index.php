<?php
$this->breadcrumbs=array(
	'Подарки'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);

$condition = new CDbCriteria();
$condition->select = 'price';
$condition->distinct = true;
$condition->order = 'price';
$costs = Presents::model()->findAll($condition);
?>

<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'presents-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'pic' => [
			'name'   => 'pic',
			'filter' => false,
			'type'   => 'raw',
			'value'  => '"<img src=\"".$data->getImageUrl()."\" style=\"max-height:32px;\">"'
		],
		'name',
		'price' => [
			'name'  => 'price',
			'type'  => 'raw',
			'filter' => CHtml::dropDownList('Presents[price]', $model->price, [null => ' - '] + CHtml::listData($costs, 'price', 'price')),
			'value' => '$data->price."<img src=\"/images/platinum.png\">"'
		],
		'hidden' => [
			'name'   => 'hidden',
			'filter' => CHtml::dropDownList('Presents[hidden]', $model->hidden, [
				null => ' - ',
				0 => 'Нет',
				1 => 'Да',
			]),
			'value' => '$data->hidden?"Да":"Нет"'
		],
		'clan' => [
			'name'  => 'clan',
			'type'  => 'raw',
			'value' => '$data->clan?CHtml::link("{$data->clanModel->name} [{$data->clan}]", array("/admin/clans/update", "id" => $data->clan)):""'
		],
		'owner' => [
			'name'  => 'owner',
			'type'  => 'raw',
			'value' => '$data->owner?CHtml::link("{$data->playerModel->user} [{$data->owner}]", array("/admin/players/update", "id" => $data->owner)):""'
		],
		[
			'header' => 'Подарено',
			'type'  => 'raw',
			'value' => '$data->getColumnGiftedCount()'
		],
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}'
		),
	),
)); ?>
