<?php $form = $this->beginWidget('MLActiveForm', array(
    'id' => 'rnews-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<?php /** @var $form MLActiveForm */ ?>

<p class="help-block">Поля помеченные <span class="required">*</span> обязательные для заполнения.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->MLtextFieldGroup($model, 'title', 'news'); ?>
<?php echo $form->MLtextFieldGroup($model, 'title_news', 'news'); ?>
<?php echo $form->MLtextFieldGroup($model, 'slug', 'news'); ?>

<?php echo $form->MLtextFieldGroup($model, 'description', 'news'); ?>
<?php echo $form->MLtextAreaVisualGroup($model, 'news', 'news'); ?>

<?php
      echo Chtml::tag('br');
      echo $form->labelEx($model, 'date');
      echo Chtml::tag('br');
      $time = strtotime($model->date);
      $this->widget('booster.widgets.TbDatePicker',array(
        'name' => 'RNews[date]',
        'value' => $time>0?date('Y-m-d', $time):date('Y-m-d'),
        'options' => array(
            'language' => 'ru',
            'format' => 'yyyy-mm-dd ',
      )));
      echo Chtml::tag('br');
?>

<?php
echo $form->labelEx($model, 'img');
  if ($model->img) {
    echo Chtml::tag('br');
    echo CHtml::image('/images/news/'.$model->img);
    echo Chtml::tag('br');
  } else {

  }
  echo $form->fileField($model, 'image');
  echo $form->error($model, 'image');
?>

<?php echo $form->dropDownListGroup($model, 'section', array('widgetOptions' => array('data' => array("news" => "Новость", "article" => "Статья", "faq" => "ЧаВо", "newuser" => "Новичкам", "games" => "Обзор",), 'htmlOptions' => array('class' => 'input-large')))); ?>
<?php echo $form->dropDownListGroup($model, 'active', array('widgetOptions' => array('data' => array("1" => "Активно", "0" => "Черновик",), 'htmlOptions' => array('class' => 'input-large')))); ?>

<div class="form-actions">
  <?php $this->widget('booster.widgets.TbButton', array(
      'buttonType' => 'submit',
      'context' => 'primary',
      'label' => $model->isNewRecord ? 'Создать' : 'Сохранить',
  )); ?>
</div>

<?php $this->endWidget(); ?>
