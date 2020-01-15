<?php
$this->breadcrumbs=array(
	'Ивенты с долларами НКР',
);

$this->menu=array(
array('label'=>'Создать ивент','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('nkr-events-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
		'model'=>$model,
	)); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'nkr-events-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'event_id',
		'name',
		'date_begin',
		'date_end',
		'news_id',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}{delete}'
		),
	),
)); ?>

