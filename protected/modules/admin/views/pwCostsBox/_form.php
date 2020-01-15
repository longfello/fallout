<?php
/**
 * @var $form MLActiveForm
 * @var $model PwCostsBox
 */
?>
<?php $form=$this->beginWidget('application.components.MLActiveForm',array(
	'id'=>'pw-costs-box-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['class' => 'esdPopup', 'enctype' => 'multipart/form-data']
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->hiddenField($model,'cost_id'); ?>

<?php echo $form->textFieldGroup($model,'name',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

<?php echo $form->numberFieldGroup($model,'chance',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<?php echo $form->MLtextFieldGroup($model,'equipment_name', 'equipment_name'); ?>
<?php echo $form->MLtextFieldGroup($model,'equipment_description', 'equipment_description'); ?>

<?php echo $form->numberFieldGroup($model,'cost',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
<?php echo $form->dropDownListGroup($model,'currency',array('widgetOptions'=>array('data' => array(
	'G' => 'Золото',
	'W' => 'Вода',
	'P' => 'Крышки',
), 'htmlOptions'=>array('class'=>'span5')))); ?>
<?php echo $form->numberFieldGroup($model,'weight',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<?php
	echo $form->fileFieldGroup($model, 'image');
	echo CHtml::image($model->getImageUrl(), '', ['style' => 'max-height:100px;max-width:300px;']);
	echo CHtml::tag('br');
	echo CHtml::tag('br');
?>

<?php
echo $form->fileFieldGroup($model, 'image_full');
echo CHtml::image($model->getImageFullUrl(), '', ['style' => 'max-height:100px;max-width:300px;']);
echo CHtml::tag('br');
echo CHtml::tag('br');
?>

<?php $this->endWidget(); ?>
