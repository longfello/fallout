<?php
$this->breadcrumbs = array(
    'Новости' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    'Редактирование',
);

$this->menu = array(
    array('label' => 'Управление новостями', 'url' => array('index')),
    array('label' => 'Добавить новость', 'url' => array('create')),
);
?>

<h1>Редактирование новости <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>