<?php
$this->breadcrumbs=array(
	'Игроки'=>array('/admin/players/index'),
	$player->user =>array('/admin/players/update', 'id' => $player->id),
	'Подарки' =>array('/admin/playerPresents2/index', 'player_id' => $player->id),
	'Подарить',
);

$this->menu=array(
	array('label'=>'Перечень','url'=>array('index', 'player_id' => $player->id)),
	array('label'=>'Подарить','url'=>array('create', 'player_id' => $player->id)),
);
?>

	<h1>Дарение подарка игроку <?php echo $player->user; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>