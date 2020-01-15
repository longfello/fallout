<?php
/* @var $this LanguageController */
/* @var $model Language */

$this->breadcrumbs=array(
	'Языки'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index'), 'icon'=>'fa fa-list'),
	array('label'=>'Добавить', 'url'=>array('create'), 'icon'=>'fa fa-plus'),
	array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id), 'icon'=>'fa fa-eye'),
	array('label'=>'Управление', 'url'=>array('admin'), 'icon'=>'fa fa-cog'),
	array('label'=>'Экспорт перевода - игра', 'url'=>array("export", "id"=>$model->id, 'type' => t::MODEL_GAME), 'icon'=>'fa fa-upload'),
	array('label'=>'Импорт перевода - игра', 'url'=>array("import", "id"=>$model->id, 'type' => t::MODEL_GAME), 'icon'=>'fa fa-download'),
	array('label'=>'Экспорт перевода - главная', 'url'=>array("export", "id"=>$model->id, 'type' => t::MODEL_HOME), 'icon'=>'fa fa-upload'),
	array('label'=>'Импорт перевода - главная', 'url'=>array("import", "id"=>$model->id, 'type' => t::MODEL_HOME), 'icon'=>'fa fa-download')
/*
	'export' => array
	(
		'label'=>'Экспорт перевода',
		'imageUrl'=>Yii::app()->request->baseUrl.'/images/export.png',
		'url'=>'',
	),
	'import' => array
	(
		'label'=>'Импорт перевода',
		'imageUrl'=>Yii::app()->request->baseUrl.'/images/import.png',
		'url'=>'Yii::app()->createUrl("translate/language/import", array("id"=>$data->id))',
	),
	*/
);
?>

<h1>Редактирование языка #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>