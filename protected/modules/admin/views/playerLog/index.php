<?php
$this->breadcrumbs=array(
	'Игрок #'.$player_id => array('/admin/players/update', 'id' => $player_id),
	'Логи',
);

$this->menu=array(
	array('label'=>'Игрок','url'=>array('/admin/players/update', 'id' => $player_id)),
);
?>

<?php $this->widget('booster.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
