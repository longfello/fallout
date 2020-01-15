<?php
$this->breadcrumbs = array(
	'robots.txt' => array( 'index' ),
	'Управление',
);

$this->menu = array(
	array( 'label' => 'Добавить', 'url' => array( 'create' ) ),
);

?>

<h1>Управление файлами robots.txt</h1>

<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'robots-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'id',
		'domain',
		'content',
		array(
			'class' => 'booster.widgets.TbButtonColumn',
			'template' => '{update}&nbsp;{delete}',
		),
	),
) ); ?>
