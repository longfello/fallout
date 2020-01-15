<?php
/**
 *
 * @var ReferalViewForm $model
 * @var TbActiveForm $form
 */
?>

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'referal-links-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->datePickerGroup($model,'begin',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy'),'htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

<?php echo $form->datePickerGroup($model,'end',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy'),'htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
		'buttonType'=>'submit',
		'context'=>'primary',
		'label'=>'Фильтровать',
	)); ?>
</div>

<?php $this->endWidget(); ?>
