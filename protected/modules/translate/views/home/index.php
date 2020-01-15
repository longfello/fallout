<?php
/* @var $this AdminController */
/* @var $model LanguageTranslate */

$this->breadcrumbs=array(
	'Переводы'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Добавить', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#language-translate-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php echo CHtml::link('Поиск','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
// $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'language-translate-grid',
  'type' => TbHtml::GRID_TYPE_HOVER,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'lang_id' => array(
        'name'=>'lang_id',
        'type'=>'html',
        'value' => 'Language::model()->findByPk($data->lang_id)->name',
        'filter'=> CHtml::dropDownList('LanguageTranslateHome[lang_id]', $model->lang_id, Chtml::listData(Language::model()->findAll(), 'id', 'name'), array( 'empty' => '-' )),
    ),
		'slug' =>  array(
        'name'=>'slug',
        'type'=>'html',
        'value' => 't::getRealSlug($data->slug)',
//        'filter'=> true,
        'htmlOptions' => array(
          'style' => 'width:40%;'
        )
    ),
		'value' => array(
        'class' => 'booster.widgets.TbEditableColumn',
        'name'=>'value',
        'type'=>'html',
//        'value' => 'mb_strlen(htmlentities($data->value, null, "UTF-8"))>100?mb_substr(htmlentities($data->value, null, "UTF-8"), 0, 100)."...":htmlentities($data->value, null, "UTF-8")',
        'editable' => array(
          'type' => 'textarea',
          'url' => $this->createUrl('home/editable'),
          'placement' => 'right',
          'inputclass' => 'span3'
        )
    ),
		'*' => array(
        'type'=>'raw',
      'value' => '$data->getIsTranslated()?"*":""',
        'filter' => CHtml::activeDropDownList($model, 'translated', array('0' => 'Все', '1' => 'Без перевода'))
    ),
		array(
        'class' => 'booster.widgets.TbButtonColumn',
      		'template' => Yii::app()->user->checkAccess('admin')?'{update}{delete}':'{update}',
		),
	),
)); ?>
