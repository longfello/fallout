<?php
$this->breadcrumbs=array(
	'Мобы'=>array('/admin/npc/index'),
	'Типы мобов'=>array('index'),
	'Управление',
);

$this->menu=array(
  array('label'=>'Мобы','url'=>array('/admin/npc/index')),
  array('label'=>'Добавить','url'=>array('create')),
);
?>

<?php $this->widget('booster.widgets.TbGridView',array(
  'id'=>'npc-type-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
      'id',
      'name',
      array(
        'class'=>'booster.widgets.TbButtonColumn',
        'template' => '{update} {delete}'
      ),
    ),
  )
); ?>
