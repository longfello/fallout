<?php
/** @var $this CController */
$this->breadcrumbs = array(
    'Настройки' => array('index')
);
?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'config-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'config_name' => array(
            'name' => 'config_name',
            'value' => 't::get("config_".$data->config_name)',
            'header' => 'Параметр',
            'filter' => false,
        ),
        'config_value' => array(
            'name' => 'config_name',
            'value' => 'Tools::inlineEdit($data, "config_value", $data->type)',
            'header' => 'Значение',
            'filter' => false
        ),
    ),
)); ?>

<nav class="navbar navbar-default">
    <ul class="nav nav-pills">
        <li role="presentation"><a href="<?= $this->createUrl('/admin/ip/index') ?>">Защита админки по IP</a></li>
    </ul>
</nav>


