<?php
/* @var $this LanguageController */
/* @var $model Language */

$this->breadcrumbs=array(
	'Языки'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'Управление', 'url'=>array('admin')),
);
?>

<h1>Добавить язык</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>