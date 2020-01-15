<?php
$this->breadcrumbs=array(
	'Реферальные ссылки' => array( 'index' ),
	$model->name=>array('update','id'=>$model->id),
	'Просмотр',
);

	$this->menu=array(
		array('label'=>'Добавить','url'=>array('create')),
		array('label'=>'Редактировать','url'=>array('update','id'=>$model->id)),
		array('label'=>'Список','url'=>array('index')),
	);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->getModule('admin')->getAssetsUrl() . '/js/plugins/chartjs/Chart.min.js', CClientScript::POS_END);
?>

<h1>Просмотр статистики по ссылке <?php echo CHtml::link($model->name, $model->getLink()) ?></h1>

<div class="row">
	<div class="col-md-12">
<?php echo $this->renderPartial('_view_form',array(
	'model'   => $searchForm,
)); ?>
	</div>
</div>

<br>

<?php echo $this->renderPartial('_view',array(
	'id'      => 'referal',
	'regData' => $regData,
	'regOpen' => $regOpen,
	'regReg'  => $regReg,
	'playersReg'  => $playersReg
)); ?>

