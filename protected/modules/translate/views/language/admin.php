<?php
/* @var $this LanguageController */
/* @var $model Language */

$this->breadcrumbs=array(
	'Языки'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить', 'url'=>array('create')),
);

?>
<?php
if(Yii::app()->user->hasFlash('message')){
	?><div class="flash-success"><?php
	echo Yii::app()->user->getFlash('message');
	?></div><?php
}
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'language-grid',
  'type' => TbHtml::GRID_TYPE_HOVER,
  'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'slug',
		'name',
		'authItem',
		array(
      'class' => 'booster.widgets.TbButtonColumn',
			'template' => '{update}{delete}',
		),
	),
)); ?>
