<?php
$this->breadcrumbs = array(
    'Rnews' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List RNews', 'url' => array('index')),
    array('label' => 'Manage RNews', 'url' => array('admin')),
);
?>

  <h1>Create RNews</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>