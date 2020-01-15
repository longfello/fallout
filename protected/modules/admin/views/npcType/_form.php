<?php
/**
 * @var $form MLActiveForm
 * @var $model NpcType
 */

  $form=$this->beginWidget('MLActiveForm',array(
    'id'=>'npc-type-form',
    'enableAjaxValidation'=>false,
  ));
?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->MLtextFieldGroup($model,'name', 'name'); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
	  'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
