<?php $form=$this->beginWidget('MLActiveForm',array(
	'id'=>'pages-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->MLtextFieldGroup($model,'name', 'name'); ?>
	<?php echo $form->MLtextAreaVisualGroup($model, 'content', 'name'); ?>

  <fieldset>
		<legend>SEO</legend>
		<?php echo $form->textFieldGroup($model,'slug',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>200)))); ?>
		<?php echo $form->MLtextFieldGroup($model,'description',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>200)))); ?>
		<?php echo $form->MLtextFieldGroup($model,'keyword',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>200)))); ?>
	</fieldset>

	<div class="form-actions">
		<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType' => 'submit',
			'context' => 'primary',
			'label' => $model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
