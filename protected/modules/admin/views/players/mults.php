<?php
/* @var $this PlayersController */
/* @var $model Players */

$this->breadcrumbs = array(
    'Игроки' => array('index'),
    'Список мультов',
);

$this->menu = array(
    array('label' => 'Перечень игроков', 'url' => array('index')),
);

?>
<?php
$dataProvider = new CArrayDataProvider($datas, array(
    'keyField' => 'ip',
    'pagination' => array(
        'pageSize' => 50,
    ),
));
?>
<?php $this->widget('ext.yiiBooster.widgets.TbGridView', array(
    'id' => 'mults-grid',
    'type' => 'striped',
    'dataProvider' => $dataProvider,
//	'filter'=>$model,
    'columns' => array(
        array(
            'name' => 'ip',
            'type' => 'raw',
            'header' => 'ip-адрес',
        ),
        array(
            'name' => 'count',
            'type' => 'raw',
            'header' => 'Количество игроков',
        ),
        array(
            'name' => 'players',
            'type' => 'html',
            'header' => 'Игроки',
            'value' => 'Users::convertNames($data)'
        ),
    ),
)); ?>
