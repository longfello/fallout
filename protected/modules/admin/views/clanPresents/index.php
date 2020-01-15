<?php
$this->breadcrumbs = array(
    'Подарки кланам' => array('index'),
    'Управление',
);

$this->menu = array(
    array('label' => 'Добавить подарок', 'url' => array('create')),
);
?>

<h1>Управление клановыми подарками</h1>


<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'clan-presents-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'pic' => array(
            'name' => 'pic',
            "type" => "raw",
            'value' => '"<img src=\"/images/podarki/clan/{$data->pic}.png\" style=\"height:75px\">"',
        ),
        'name',
        'desc',
        'clan' => array(
            'name' => 'clan',
            'filter' => CHtml::dropDownList('ClanPresents[clan]', $model->clan,
                [null => '-'] + CHtml::listData(Clans::model()->findAll(array('order' => 'id')), 'id', 'name')
            )
        ),
        array(
            'class' => 'booster.widgets.TbButtonColumn',
            'template' => '{update}&nbsp;{delete}'
        ),
    ),
)); ?>
