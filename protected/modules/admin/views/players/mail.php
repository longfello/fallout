<?php
/* @var $this PlayersController */
/* @var $model MailForm  */
/* @var $form TbActiveForm */

$this->breadcrumbs = array(
    'Игроки' => array('index'),
    'Рассылка',
);

$count = Email::model()->count();
$info  = $count?['label' => '<div class="alert-info" title="Письма разсылаются в фоновом режиме. Отправка начинается каждый час.">В очереди на рассылку писем: '.$count.'</div>']:['label' => '<div class="alert-success">Все письма разосланы</div>'];

$this->menu = array(
    array('label' => 'Перечень игроков', 'url' => array('index')),
    $info,
);

?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
  //'id'=>'login-form',
    'action' => CController::createUrl('players/mail'),
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>

  <?php
     $form->errorSummary($model);
  ?>

  <?php echo $form->labelEx($model, 'type') ?>
  <?php echo $form->hiddenField($model, 'type') ?>
  <ul class="nav nav-tabs mail-type" role="tablist">
    <?php $first = true; ?>
    <?php foreach ($model->available_types as $slug => $name ) { ?>
      <li role="presentation" <?= $first?'class="active"':'';?>><a href="#sendtype-<?=$slug?>" aria-controls="sendtype-<?=$slug?>" role="tab" data-toggle="tab" data-type="<?=$slug?>"><?=$name?></a></li>
      <?php $first = false; ?>
    <?php } ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php $first = true; ?>
    <?php foreach ($model->available_types as $slug => $name ) { ?>
      <div role="tabpanel" class="tab-pane <?= $first?'active':'';?>" id="sendtype-<?=$slug?>">
        <div class="well">
          <?php $this->renderPartial('mail/_type_'.$slug, array('model' => $model, 'form' => $form)); ?>
        </div>
      </div>
      <?php $first = false; ?>
    <?php } ?>
  </div>


  <?php echo $form->radioButtonListGroup($model, 'send_type', array('widgetOptions'=>array('data'=>array("email"=>" E-mail","gmail"=>" Игровая почта","log"=>" Игровой лог")))); ?>
  <?php echo $form->textFieldGroup($model, 'sender'); ?>
  <?php echo $form->textFieldGroup($model, 'sender_en'); ?>
  <?php echo $form->numberFieldGroup($model, 'sender_id'); ?>
  <?php echo $form->textFieldGroup($model, 'title'); ?>
  <?php echo $form->textFieldGroup($model, 'title_en'); ?>

  <?php echo $form->labelEx($model, 'content') ?>
  <?php echo $this->widget('bootstrap.widgets.TbCKEditor',
    array(
        'model'     => $model,
        'attribute' => 'content'
    ), true);
  ?>
  <?php echo $form->error($model, 'content') ?>

  <?php echo $form->labelEx($model, 'content_en') ?>
  <?php echo $this->widget('bootstrap.widgets.TbCKEditor',
      array(
          'model'     => $model,
          'attribute' => 'content_en'
      ), true);
  ?>
  <?php echo $form->error($model, 'content_en') ?>

  <div class="footer">
    <?php echo CHtml::submitButton('Отправить', array('class' => 'btn bg-blue btn-block')); ?>
  </div>

<?php $this->endWidget(); ?>

<?php

  Yii::app()->clientScript->registerScript("mailing", "
$('.mail-type a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) {
  $('#MailForm_type').val($(e.target).data('type'));
});
  ", CClientScript::POS_READY);