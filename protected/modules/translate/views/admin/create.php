<?php
/* @var $this AdminController */
/* @var $model LanguageTranslate */

$this->breadcrumbs=array(
	'Переводы'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Управление', 'url'=>array('index')),
);
?>

<h1>Добавление перевода</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>