<div class="form">

<?php
/** @var $form TbActiveForm */
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
));
?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary(array($model,$profile)); ?>

		<?php echo $form->textFieldGroup($model,'username'); ?>
		<?php echo $form->passFieldGroup($model,'password',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->textFieldGroup($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->dropDownListGroup($model,'superuser',array('widgetOptions' => array('data' => User::itemAlias('AdminStatus')))); ?>
		<?php echo $form->dropDownListGroup($model,'status', array('widgetOptions' => array('data' => User::itemAlias('UserStatus')))); ?>
    <?php
  		$profileFields=$profile->getFields();
  		if ($profileFields) {
  			foreach($profileFields as $field) {
			?>
      <?php
      if ($widgetEdit = $field->widgetEdit($profile)) {
        echo $widgetEdit;
      } elseif ($field->range) {
        echo $form->dropDownListGroup($profile,$field->varname,Profile::range($field->range));
      } elseif ($field->field_type=="TEXT") {
        echo CHtml::activeTextArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
      } else {
        echo $form->textFieldGroup($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
      }
       ?>
      <?php echo $form->error($profile,$field->varname); ?>
      <?php
      }
		}
?>
	<div class=" buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->