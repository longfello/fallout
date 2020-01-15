<?php
/* @var $this PlayersController */
/* @var $model BannedPlayers */
/* @var $form TbActiveForm */
$this->pageTitle .= ' - редактирование правила блокировки';
?>

<div class="form">

<?php $form=$this->beginWidget('TbActiveForm', array(
	'id'=>'banned-players-updateBan-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->hiddenField($model,'player_id'); ?>
	<?php echo (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin')))?$form->checkboxGroup($model,'block_ip'):''; ?>
	<?php echo (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_chat')))?$form->checkboxGroup($model,'chat'):''; ?>
	<?php echo (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_site')))?$form->checkboxGroup($model,'site'):''; ?>
	<?php echo (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_advert')))?$form->checkboxGroup($model,'advert'):''; ?>

	<?php echo $form->textFieldGroup($model,'login'); ?>
  <?php
    $datas = array(
      date('Y-m-d H:i:s', strtotime('now + 5 minute')) => '5 минут',
      date('Y-m-d H:i:s', strtotime('now + 10 minute')) => '10 минут',
      date('Y-m-d H:i:s', strtotime('now + 15 minute')) => '15 минут' ,
      date('Y-m-d H:i:s', strtotime('now + 30 minute')) => '30 минут',
      date('Y-m-d H:i:s', strtotime('now + 1 hour')) => '1 час',
      date('Y-m-d H:i:s', strtotime('now + 6 hours')) => '6 часов',
      date('Y-m-d H:i:s', strtotime('now + 1 day')) => '1 день',
      date('Y-m-d H:i:s', strtotime('now + 2 days')) => '2 дня',
      date('Y-m-d H:i:s', strtotime('now + 3 days')) => '3 дня',
      date('Y-m-d H:i:s', strtotime('now + 5 days')) => '5 дней',
      date('Y-m-d H:i:s', strtotime('now + 1 week')) => '1 неделя',
      date('Y-m-d H:i:s', strtotime('now + 1 month')) => '1 месяц',
      date('Y-m-d H:i:s', strtotime('now + 1 year')) => '1 год',
      'Всегда' => 'всегда',
    );

    $labels = "";
    foreach($datas as $data => $name) {
      $labels .= "<a href='#' class='date-time-speed-dial btn btn-default btn-sm' data-time='{$data}'>{$name}</a>&nbsp;";
    }
  ?>
	<?php echo $form->dateTimePickerGroup($model,'until', array(
    'label' => $model->getAttributeLabel('until').'&nbsp;'.$labels
  )); ?>
	<?php echo $form->textFieldGroup($model,'reason'); ?>
	<?php echo $form->textAreaGroup($model,'comment'); ?>


	<div class="form-group">
		<?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php

  Yii::app()->clientScript->registerScript('date-time-speed-dial', "
$('a.date-time-speed-dial').on('click', function(e){
  e.preventDefault();
  $('#BannedPlayers_until').val($(this).data('time'));
});  
  ", CClientScript::POS_READY);
