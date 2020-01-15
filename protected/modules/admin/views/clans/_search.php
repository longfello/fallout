<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); ?>

<?php echo $form->textFieldGroup($model, 'id', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textFieldGroup($model, 'name', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'owner', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textFieldGroup($model, 'coowner', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textFieldGroup($model, 'gold', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textFieldGroup($model, 'platinum', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textAreaGroup($model, 'public_msg', array('widgetOptions' => array('htmlOptions' => array('rows' => 6, 'cols' => 50, 'class' => 'span8')))); ?>

<?php echo $form->textAreaGroup($model, 'private_msg', array('widgetOptions' => array('htmlOptions' => array('rows' => 6, 'cols' => 50, 'class' => 'span8')))); ?>

<?php echo $form->textAreaGroup($model, 'tag', array('widgetOptions' => array('htmlOptions' => array('rows' => 6, 'cols' => 50, 'class' => 'span8')))); ?>

<?php echo $form->passwordFieldGroup($model, 'pass', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 30)))); ?>

<?php //echo $form->textFieldGroup($model, 'moneypass', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 30)))); ?>

<?php echo $form->textFieldGroup($model, 'hospass', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 1)))); ?>

<?php //echo $form->textFieldGroup($model, 'clanwars', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 1)))); ?>

<?php //echo $form->textFieldGroup($model, 'chatroom', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 1)))); ?>

<?php //echo $form->textFieldGroup($model, 'clanstore', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 1)))); ?>

<?php echo $form->textFieldGroup($model, 'clan_tax_ranges', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'war_win', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textFieldGroup($model, 'union_win', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<div class="form-actions">
  <?php $this->widget('booster.widgets.TbButton', array(
      'buttonType' => 'submit',
      'context' => 'primary',
      'label' => 'Search',
  )); ?>
</div>

<?php $this->endWidget(); ?>
