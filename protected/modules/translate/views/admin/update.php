<?php
/* @var $this AdminController */
/* @var $model LanguageTranslate */

$this->breadcrumbs=array(
	'Переводы'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Управление', 'url'=>array('index')),
	array('label'=>'Добавить', 'url'=>array('create')),
);
?>

<h1>Редактирование перевода строки <?php echo t::getRealSlug($model->slug); ?></h1>

<?php $this->renderPartial('_update', array('models' => $models, 'languages' => $languages, 'all_languages' => $all_languages)); ?>