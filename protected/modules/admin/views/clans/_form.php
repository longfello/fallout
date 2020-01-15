<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'clans-form',
    'enableAjaxValidation' => false,
));

/** @var $form TbActiveForm */
?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldGroup($model, 'name', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 255)))); ?>
<?php echo $form->textFieldGroup($model, 'owner', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
<?php echo $form->textFieldGroup($model, 'coowner', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
<?php //echo $form->textFieldGroup($model, 'gold', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
<?php //echo $form->textFieldGroup($model, 'platinum', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
<div class="form-group">
  <?php echo $form->labelEx($model, 'public_msg') ?>
  <?php $this->widget(
      'bootstrap.widgets.TbCKEditor', array(
      'model' => $model,
      'attribute' => 'public_msg',
  ));
  ?>
</div>
<div class="form-group">
  <?php echo $form->labelEx($model, 'private_msg') ?>
  <?php $this->widget(
      'bootstrap.widgets.TbCKEditor', array(
      'model' => $model,
      'attribute' => 'private_msg',
  ));
  ?>
</div>
<?php echo $form->textAreaGroup($model, 'tag', array('widgetOptions' => array('htmlOptions' => array('rows' => 6, 'cols' => 50, 'class' => 'span8')))); ?>
<?php echo $form->textFieldGroup($model, 'pass', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 30)))); ?>
<?php echo $form->dropDownListGroup($model, 'hospass', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 1), 'data' => array('N' => 'Нет', 'Y' => 'Да')))); ?>
<?php echo $form->textFieldGroup($model, 'clan_tax_ranges', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 255)))); ?>
<?php echo $form->textFieldGroup($model, 'war_win', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>
<?php echo $form->textFieldGroup($model, 'union_win', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<div class="form-actions">
  <?php $this->widget('booster.widgets.TbButton', array(
      'buttonType' => 'submit',
      'context' => 'primary',
      'label' => $model->isNewRecord ? 'Создать' : 'Сохранить',
  )); ?>
</div>

<?php $this->endWidget(); ?>
