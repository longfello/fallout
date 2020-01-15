<?php
/**
 * @var $form TbActiveForm
 */
?>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'pw-costs-content-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['class' => 'esdPopup']
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->hiddenField($model,'box_id'); ?>

<?php echo $form->textFieldGroup($model,'name',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

<?php echo $form->numberFieldGroup($model,'chance',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<?php $this->endWidget(); ?>
