<?php
	$this->breadcrumbs=array(
		'Подарки'=>array('/admin/presents/index'),
		$present->name =>array('/admin/presents/update', 'id' => $present->id),
		'Детали' =>array('/admin/playerPresents/index', 'present_id' => $present->id),
		'Редактировать',
	);

	$this->menu=array(
		array('label'=>'Перечень','url'=>array('index', 'present_id' => $present->id)),
		array('label'=>'Подарить','url'=>array('create', 'present_id' => $present->id)),
	);
	?>

	<h1>Редактирование подарка &laquo;<?= $present->name ?>&raquo; игроку <?php echo $model->ownerModel->user; ?></h1>

	<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>