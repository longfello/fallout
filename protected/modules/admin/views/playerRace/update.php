<?php
	$this->breadcrumbs=array(
		'Расы персонажей'=>array('index'),
		'Редактирование',
	);

	$this->menu=array(
		array('label'=>'Перечень','url'=>array('index')),
		array('label'=>'Внешний вид','url'=>array('/admin/playerRaceAppearanceList/index', 'race_id' => $model->id)),
		array('label'=>'Добавить расу','url'=>array('create')),
	);
?>

<h1>Редактирование расы <?php echo $model->name; ?> [<?= $model->id ?>]</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model, 'benefits' => $benefits)); ?>