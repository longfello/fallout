<?php
/* @var $this CombatController */
/* @var $model Players */
/* @var $form TbActiveForm */
?>

<b><?= $model->user ?></b>
<div class="form player_settings" id="player_<?= $model->id ?>">

	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'players-form',
		'type' => 'horizontal',
		'enableAjaxValidation' => false,
	)); ?>

	<?php echo $form->errorSummary($model); ?>
	<div class="well">
		<?php echo $form->textFieldGroup($model, 'level'); ?>
		<?php echo $form->textFieldGroup($model, 'energy'); ?>
		<?php echo $form->textFieldGroup($model, 'strength'); ?>
		<?php echo $form->textFieldGroup($model, 'agility'); ?>
		<?php echo $form->textFieldGroup($model, 'hp'); ?>
		<?php echo $form->textFieldGroup($model, 'defense'); ?>
		<?php echo $form->hiddenField($model, 'id'); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
