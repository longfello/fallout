<?php
$this->breadcrumbs = array(
    'Кланы' => array('index'),
    $model->name => array('update', 'id' => $model->id),
    'Редактирование',
);

$this->menu = array(
    array('label' => 'Перечень кланов', 'url' => array('admin')),
    array('label' => 'Создать клан', 'url' => array('create')),
    array('label' => 'Журнал кладовки', 'url' => array('/admin/clanStoreHistory', 'clan_id' => $model->id)),
);
?>

  <h1>Редактирование клана [<?php echo $model->id; ?>] <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>