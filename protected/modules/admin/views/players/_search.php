<?php
/* @var $this PlayersController */
/* @var $model Players */
/* @var $form CActiveForm */
?>

<div class="wide form">

  <?php $form = $this->beginWidget('CActiveForm', array(
      'action' => Yii::app()->createUrl($this->route),
      'method' => 'get',
      'htmlOptions' => array(
          'class' => 'form-horizontal'
      )
  )); ?>

  <div class="form-group">
    <div class="form-group">
      <?php echo $form->label($model, 'id', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10">
        <?php echo $form->textField($model, 'id', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'user', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'user', array('size' => 15, 'maxlength' => 15, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'email', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 60, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'pass', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->passwordField($model, 'pass', array('size' => 60, 'maxlength' => 60, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'pass2', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'pass2', array('size' => 60, 'maxlength' => 60, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'question', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'question', array('size' => 60, 'maxlength' => 64, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'answer', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'answer', array('size' => 60, 'maxlength' => 64, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rank', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'rank', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'level', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'level', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'gold', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'gold', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'exp', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'exp', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'energy', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'energy', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'max_energy', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'max_energy', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'strength', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'strength', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'agility', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'agility', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'hp', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'hp', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'max_hp', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'max_hp', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'bank', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'bank', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'ap', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'ap', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'wins', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'wins', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'losses', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'losses', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'tag', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'tag', array('size' => 15, 'maxlength' => 15, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'platinum', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'platinum', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'age', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'age', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'gender', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'gender', array('size' => 11, 'maxlength' => 11, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'location', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'location', array('size' => 15, 'maxlength' => 15, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'profile', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'profile', array('size' => 60, 'maxlength' => 1500, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'msn', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'msn', array('size' => 30, 'maxlength' => 30, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'logins', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'logins', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'lpv', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'lpv', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'page', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'page', array('size' => 60, 'maxlength' => 100, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'ip', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'ip', array('size' => 50, 'maxlength' => 50, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'clan', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'clan', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'ClanThresholdTime', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'ClanThresholdTime', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'PreviousClanId', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'PreviousClanId', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'clan_money', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'clan_money', array('size' => 1, 'maxlength' => 1, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'clan_store', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'clan_store', array('size' => 1, 'maxlength' => 1, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'refs', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'refs', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'mines', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'mines', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'ops', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'ops', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'alethite', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'alethite', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'burelia', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'burelia', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'email_confirmed', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->dropDownList($model, 'email_confirmed', array('No' => 'Нет', 'Yes' => 'Да'), array('size' => 3, 'maxlength' => 3, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'defense', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'defense', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'opit', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'opit', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'napad', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'napad', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'ewins', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'ewins', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'pohod', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'pohod', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
<!--
  <div class="form-group">
    <div class="row">
      <?php //echo $form->label($model, 'work2', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php // echo $form->textField($model, 'work2', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
-->
  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'q_gauss', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'q_gauss', array('size' => 1, 'maxlength' => 1, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'nablud', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'nablud', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'pleft', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'pleft', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'impl', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'impl', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'hidde_in_records', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'hidde_in_records', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
<!--
  <div class="form-group">
    <div class="row">
      <?php //echo $form->label($model, 'lstate', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php //echo $form->textField($model, 'lstate', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
-->
  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'chattime', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'chattime', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'radiotime', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'radiotime', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'chatclantime', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'chatclantime', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'chattimecaves', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'chattimecaves', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'logme', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'logme', array('size' => 1, 'maxlength' => 1, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'etm', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'etm', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_ts_d', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'rq_ts_d', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_id_d', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_id_d', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_ts_h', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_ts_h', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_id_h', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_id_h', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_ok', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'rq_ok', array('size' => 1, 'maxlength' => 1, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_st', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_st', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_drop1_st', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_drop1_st', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_drop2_st', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_drop2_st', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_cnt', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'rq_cnt', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'rq_from', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'rq_from', array('size' => 4, 'maxlength' => 4, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'distance', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'distance', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'x', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'x', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'y', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'y', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'buy_submob_info', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'buy_submob_info', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'last_wrong_login_cnt', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'last_wrong_login_cnt', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'last_wrong_login_time', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'last_wrong_login_time', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'mail_limit', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'mail_limit', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'read_news', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'read_news', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'buy_map_pustosh', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'buy_map_pustosh', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'ref', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'ref', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'unv', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'unv', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'reg_date', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'reg_date', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'wins_quest', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'wins_quest', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'vk_id', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'vk_id', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'unv_start', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'unv_start', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'unv_ban', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'unv_ban', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'last_visit_toxic_caves', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'last_visit_toxic_caves', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'nomail', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'nomail', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>
<!--
  <div class="form-group">
    <div class="row">
      <?php //echo $form->label($model, 'refs_cityads', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php //echo $form->textField($model, 'refs_cityads', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>
-->
<!--
  <div class="form-group">
    <div class="row">
      <?php //echo $form->label($model, 'prx_cityads', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php //echo $form->textField($model, 'prx_cityads', array('size' => 20, 'maxlength' => 20, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>
-->
  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'token', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'token', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'travel_place', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'travel_place', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'labyrinth_y', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'labyrinth_y', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'labyrinth_x', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'labyrinth_x', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'provider', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'provider', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'provider_uid', array('class' => 'col-sm-2 control-label')); ?>
      <div
          class="col-sm-10"><?php echo $form->textField($model, 'provider_uid', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group">
    <div class="row">
      <?php echo $form->label($model, 'automove', array('class' => 'col-sm-2 control-label')); ?>
      <div class="col-sm-10"><?php echo $form->textField($model, 'automove', array('class' => 'form-control')); ?>
      </div>
    </div>
  </div>

  <div class="form-group row buttons">
    <div class="col-sm-offset-2 col-sm-10">
      <?php echo CHtml::submitButton('Найти', array('class' => 'btn btn-primary')); ?>
    </div>
  </div>


  <?php $this->endWidget(); ?>

</div><!-- search-form -->