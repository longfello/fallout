<?php
$this->breadcrumbs = array(
    'Новости' => array('index'),
    'Управление',
);

$this->menu = array(
    array('label' => 'Добавить новость', 'url' => array('create')),
);

Yii::app()->getModule('thumbler');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('rnews-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
  <?php $this->renderPartial('_search', array(
      'model' => $model,
  )); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'rnews-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
          'name' => 'img',
          "type" => "raw",
          'value' => '"<img src=\"".Thumbler::getUrl("news/{$data->img}", 64, 64)."\" >"',
        ),
        array(
          'name' => 'title',
          'type' => 'raw',
          'value' => '$data->getML("title")'
        ),
        array(
          'name' => 'description',
          'type' => 'raw',
          'value' => '$data->getML("description")'
        ),
        array(
          'name' => 'news',
            'type' => 'raw',
            'value' => 'CString::truncate(strip_tags($data->getML("news")), 300, "...")'
        ),
        array(
          'name' => 'date',
          'type' => 'raw',
          'value' => '(strtotime($data->date)>0)?date("d.m.Y", strtotime($data->date)):"&nbsp;"',
        ),
        array(
          'name' => 'section',
          'filter' => CHtml::activeDropDownList($model, 'section', array(null => '', "news" => "Новость", "article" => "Статья", "faq" => "ЧаВо", "newuser" => "Новичкам", "games" => "Обзор")),
        ),
        array(
            'class' => 'booster.widgets.TbButtonColumn',
            'template' => '{view}{update}{delete}',
            'buttons' => array(
                'view' => array(
                    'label' => 'Просмотр',
//            'imageUrl'=>Yii::app()->request->baseUrl.'/images/ban.png',
                    'icon' => 'fa fa-eye',
                    'url' => 'Yii::app()->createUrl("new/".$data->id)',
                    'options' => array('target' => '_new'),
                ),
            )
        ),
    ),
)); ?>


