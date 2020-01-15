<?php
$this->breadcrumbs = array(
	'Реферальные ссылки' => array( 'index' ),
	'Управлние',
);

$this->menu = array(
	array( 'label' => 'Добавить', 'url' => array( 'create' ) ),
	array( 'label' => 'Статистика', 'url' => array( 'stat' ) ),
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->getModule('admin')->getAssetsUrl() . '/js/plugins/chartjs/Chart.min.js', CClientScript::POS_END);

$this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'referal-links-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'id',
		'name',
		'slug',
		'link' => array(
			'header' => 'Ссылка',
			'type'   => 'raw',
			'value'  => '$data->getLink()'
		),
		'redirect_url',
		array(
			'class' => 'booster.widgets.TbButtonColumn',
			'template' => '{view}&nbsp;{update}&nbsp;{delete}',
		),
	),
) ); ?>
