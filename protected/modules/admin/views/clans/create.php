<?php
$this->breadcrumbs = array(
    'Кланы' => array('index'),
    'Создать',
);

$this->menu = array(
    array('label' => 'Управление кланами', 'url' => array('admin')),
);
?>

  <h1>Создать клан</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>