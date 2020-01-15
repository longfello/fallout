<?php
$this->breadcrumbs = array(
    'Набитые татуировки' => array('index'),
    'Управление',
);

$this->menu = array(
    array('label' => 'Набить тату', 'url' => array('create')),
    array('label' => 'Список всех тату', 'url' => array('tatoos/index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('utatoos-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Набитые татуировки</h1>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'utatoos-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'owner' => [
            'name'  => 'owner',
            'type'  => 'raw',
            'value' => '$data->owner?CHtml::link("{$data->playerModel->user} [{$data->owner}]", array("/admin/players/update", "id" => $data->owner)):""'
        ],
        'regens',
        'tatoo'=> [
            'name'  => 'tatoo',
            'type'  => 'raw',
            'value' => '$data->tatoo?CHtml::link("{$data->tatooModel->name} [{$data->tatoo}]", array("/admin/tatoos/update", "id" => $data->tatoo)):""'
        ],
        array(
            'name' => 'lifetime',
            'type'  => 'raw',
            'value' => '$data->lifetime?date("Y-m-d h:m:s",$data->lifetime):"-"'
        ),
        'timeout' => array(
            'name'        => 'timeout',
            'filter'      => CHtml::dropDownList( 'Utatoos[timeout]', $model->timeout,
                [
                    null => '-',
                    '1' => 'Да',
                    '0' => 'Нет',
                ]
            )
        ),
        array(
            'class' => 'booster.widgets.TbButtonColumn',
            'template' => '{update}&nbsp;{delete}',
        ),
    ),
)); ?>
