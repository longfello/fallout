<?php
$this->breadcrumbs = array(
    'Цены крышек' => array('index'),
    'Управление',
);

$this->menu = array(
    array('label' => 'Создать цену', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('pw-costs-grid', {
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
    'id' => 'pw-costs-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'platinum',
        'price',
        'discount',
        array(
            'name' => 'image',
            "type" => "raw",
            'value' => '"<img src=\"/images/costs/{$data->image}\" style=\"height:64px\">"',
        ),
        array(
            'class' => 'booster.widgets.TbButtonColumn',
            'template' => '{update}{delete}'
        ),
    ),
)); ?>
