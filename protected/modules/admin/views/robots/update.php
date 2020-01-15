<?php
$this->breadcrumbs = array(
	'robots.txt'   => array( 'index' ),
	'Редактирование',
);

$this->menu = array(
	array( 'label' => 'Добавить', 'url' => array( 'create' ) ),
	array( 'label' => 'Управление', 'url' => array( 'admin' ) ),
);
?>

<h1>Редактирование файла robots.txt для домена <?php echo $model->domain; ?></h1>

<?php echo $this->renderPartial( '_form', array( 'model' => $model ) ); ?>