<?php
$this->breadcrumbs = array(
	'Бонусные ящики' => array( 'index' ),
	'Управление',
);

$this->menu = array(
	array( 'label' => 'Добавить ящик', 'url' => array( 'create' ) ),
);
?>

<h1>Бонусные ящики</h1>

<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'pw-costs-box-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'id',
		'name',
		'chance',
		array(
			'class' => 'booster.widgets.TbButtonColumn',
		),
	),
) ); ?>
