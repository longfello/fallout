<?php
/**
 * @var $form MLActiveForm
 * @var $model NkrPrices
 */
?>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'nkr-prices-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['class' => 'esdPopup']
)); ?>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'event_id'); ?>

	<?php echo $form->textFieldGroup($model,'sum',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'mobs',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'bonus',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<?php $this->endWidget(); ?>
