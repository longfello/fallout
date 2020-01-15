<div class="header"><?php echo Yii::t('app', 'Sign in'); ?></div>

<?php $form = $this->beginWidget('CActiveForm', array(
  //'id'=>'login-form',
    'action' => CController::createUrl('default/login'),
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
  //'htmlOptions'=>array('class'=>'form-signin')
)); ?>

<div class="body bg-gray">
  <div class="form-group">
    <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'placeholder' => Yii::t('app', 'User ID'))); ?>
    <?php echo $form->error($model, 'username'); ?>
  </div>
  <div class="form-group">
    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => Yii::t('app', 'Password'))); ?>
    <?php echo $form->error($model, 'password'); ?>
  </div>
</div>
<div class="footer">
  <?php echo CHtml::submitButton(Yii::t('app', 'Sign me In'), array('class' => 'btn bg-blue btn-block')); ?>
</div>

<?php $this->endWidget(); ?>