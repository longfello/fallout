<?php
$this->breadcrumbs=array(
	'Статические страницы'=>array('index'),
);

$this->menu=array(
array('label'=>'Создать','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('pages-grid', {
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
'id'=>'pages-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'description',
		'keyword',
  array(
    'name' => 'name',
    'type' => 'raw',
    'value' => '$data->getML("name")',
  ),
		'slug',
  array(
    'name' => 'content',
    'type' => 'raw',
    'value' => 'RText::truncateTextCount(strip_tags($data->getML("content")), 300, "...")',
  ),
		'id',
  array(
    'class' => 'booster.widgets.TbButtonColumn',
    'template' => '{view}{update}{delete}',
    'buttons' => array(
      'view' => array(
        'label' => 'Просмотр',
//            'imageUrl'=>Yii::app()->request->baseUrl.'/images/ban.png',
        'icon' => 'fa fa-eye',
        'url' => 'Yii::app()->createUrl("page/".$data->slug)',
        'options' => array('target' => '_new'),
      ),
    )
  ),
),
)); ?>
