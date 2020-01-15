<?php
$this->breadcrumbs = array(
  'Ошибки' => array('index'),
  'Журнал',
);

$this->menu = array(
  array('label' => 'Очистить', 'url' => array('truncate')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('errors-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
  <?php $this->renderPartial('_search', array(
    'model' => $model,
  )); ?>
</div><!-- search-form -->

<?php
  $form = $this->beginWidget('CActiveForm', [
	  'action' => Yii::app()->createUrl('/admin/errors/erase'),
	  'method' => 'post',
	  'htmlOptions' => array(
		  'class' => 'form-inline pull-right'
	  )
  ]);
?>
<?php echo CHtml::textField('q', Yii::app()->request->getPost('q', ''), array('class'=> 'form-control','size'=>60,'maxlength'=>128, 'placeholder' => 'Строка для удаление')); ?>
<?php echo CHtml::submitButton('Удалить', ['class' => 'btn btn-danger']); ?>
<?php $this->endWidget(); ?>

<?php $this->widget('booster.widgets.TbGridView', array(
  'id' => 'errors-grid',
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    'level',
    'category',
    'logtime' => array(
      'name' => 'logtime',
      'type' => 'raw',
      'value' => 'date("d.m.Y H:i:s", $data->logtime)'
    ),
    'message' => array(
      'name' => 'message',
      'type' => 'text',
      'value' => 'mb_substr($data->message, 0, 250)',
    ),
    array(
      'class' => 'booster.widgets.TbButtonColumn',
      'template' => '{view}&nbsp;{delete}'
    ),
  ),
)); ?>
