<?php
$this->breadcrumbs=array(
	'Татуировки'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
	array('label'=>'Набитые тату','url'=>array('utatoos/index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('tatoos-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Управление татуировками</h1>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
		'model'=>$model,
	)); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'tatoos-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'owner',
		'clan' => array(
			'name'        => 'clan',
			'filter'      => CHtml::dropDownList( 'Tatoos[clan]', $model->clan,
				[null => '-', '0' => 'Общедоступный']+CHtml::listData( Clans::model()->findAll( array( 'order' => 'name' ) ), 'id', 'name' )
			)
		),
		'name',
		'cost',
		'mtype' => array(
			'name'        => 'mtype',
			'filter'      => CHtml::dropDownList( 'Tatoos[mtype]', $model->mtype,
				[
					null => '-',
					'gold' => 'Золото',
					'platinum' => 'Крышки',
				]
			),
			'value' => '$data->getMTypeName()'
		),
		'minlev',
		'days',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}',
		),
	),
)); ?>
