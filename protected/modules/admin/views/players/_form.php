<?php
/* @var $this PlayersController */
/* @var $model Players */
/* @var $form TbActiveForm */
?>

<div class="form">

  <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'id' => 'players-form',
      'type' => 'horizontal',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
      'enableAjaxValidation' => false,
  )); ?>

  <p class="note">Поля, отмеченные <span class="required">*</span> обязательные для заполнения.</p>

  <?php echo $form->errorSummary($model); ?>
  <?php echo CHtml::hiddenField('oldData', CJavaScript::jsonEncode($model->getBasicData())) ?>

  <div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#ut1" aria-controls="ut1" role="tab" data-toggle="tab">Основное</a></li>
      <li role="presentation"><a href="#ut4" aria-controls="ut4" role="tab" data-toggle="tab">Описание</a></li>
      <li role="presentation"><a href="#ut2" aria-controls="ut2" role="tab" data-toggle="tab">Ресурсы</a></li>
      <li role="presentation"><a href="#ut3" aria-controls="ut3" role="tab" data-toggle="tab">Характеристики</a></li>
      <li role="presentation"><a href="#ut8" aria-controls="ut8" role="tab" data-toggle="tab">Параметры</a></li>
      <li role="presentation"><a href="#ut6" aria-controls="ut6" role="tab" data-toggle="tab">Клан</a></li>
      <li role="presentation"><a href="#ut9" aria-controls="ut9" role="tab" data-toggle="tab">Квесты</a></li>
      <li role="presentation"><a href="#ut7" aria-controls="ut7" role="tab" data-toggle="tab">Логово</a></li>
      <li role="presentation"><a href="#ut11" aria-controls="ut11" role="tab" data-toggle="tab">Локация</a></li>
      <li role="presentation"><a href="#ut5" aria-controls="ut5" role="tab" data-toggle="tab">Статистика</a></li>
      <li role="presentation"><a href="#ut12" aria-controls="ut12" role="tab" data-toggle="tab">Аватар в чате</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="ut1">
        <br>
        <?php echo $form->textFieldGroup($model, 'user', array('size' => 15, 'maxlength' => 15)); ?>
        <?php $raceData = array_merge(['Не выбрана'], CHtml::listData(PlayerRace::model()->findAll(), 'id', 'name'));  ?>
        <?php echo $form->dropDownListGroup($model, 'race_id', array('widgetOptions' => array('data' => $raceData))); ?>
        <?php echo $form->textFieldGroup($model, 'email', array('size' => 60, 'maxlength' => 60)); ?>
        <?php echo $form->textFieldGroup($model, 'msn', array('size' => 30, 'maxlength' => 30)); ?>
        <?php echo $form->dropDownListGroup($model, 'gender', array('widgetOptions' => array('data' => array(Players::GENDER_MALE => Players::GENDER_MALE, Players::GENDER_FEMALE => Players::GENDER_FEMALE)))); ?>
        <?php echo $form->textFieldGroup($model, 'question', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->textFieldGroup($model, 'answer', array('size' => 60, 'maxlength' => 64)); ?>
        <?php echo $form->dropDownListGroup($model, 'rank', array('widgetOptions' => array('data' => array('Игрок' => 'Игрок', 'Модер' => 'Модер', 'Чат-Модер' => 'Чат-Модер', 'Админ' => 'Админ')))); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut2">
        <br>
        <?php echo $form->textFieldGroup($model, 'gold'); ?>
        <?php echo $form->textFieldGroup($model, 'bank'); ?>
        <?php echo $form->textFieldGroup($model, 'platinum'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut3">
        <br>
        <?php echo $form->textFieldGroup($model, 'age'); ?>
        <?php echo $form->textFieldGroup($model, 'level'); ?>
        <?php echo $form->textFieldGroup($model, 'exp'); ?>
        <?php echo $form->textFieldGroup($model, 'energy'); ?>
        <?php echo $form->textFieldGroup($model, 'max_energy'); ?>
        <?php echo $form->textFieldGroup($model, 'strength'); ?>
        <?php echo $form->textFieldGroup($model, 'agility'); ?>
        <?php echo $form->textFieldGroup($model, 'hp'); ?>
        <?php echo $form->textFieldGroup($model, 'max_hp'); ?>
        <?php echo $form->textFieldGroup($model, 'ap'); ?>
        <?php echo $form->textFieldGroup($model, 'defense'); ?>
        <?php echo $form->textFieldGroup($model, 'opit'); ?>
        <?php echo $form->textFieldGroup($model, 'napad'); ?>
        <?php echo $form->textFieldGroup($model, 'ewins'); ?>
        <?php echo $form->textFieldGroup($model, 'pohod'); ?>
        <?php echo $form->textFieldGroup($model, 'nablud'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut4">
        <br>
        <?php echo $form->textFieldGroup($model, 'location', array('size' => 15, 'maxlength' => 15)); ?>
        <?php echo $form->textFieldGroup($model, 'profile', array('size' => 60, 'maxlength' => 1500)); ?>
        <?php echo $form->textFieldGroup($model, 'tag', array('size' => 15, 'maxlength' => 15)); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut5">
        <br>
        <?php echo $form->textFieldGroup($model, 'wins'); ?>
        <?php echo $form->textFieldGroup($model, 'losses'); ?>
        <?php echo $form->textFieldGroup($model, 'logins'); ?>
        <?php echo $form->textFieldGroup($model, 'lpv', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'page', array('size' => 60, 'maxlength' => 100)); ?>
        <?php echo $form->textFieldGroup($model, 'ip', array('size' => 50, 'maxlength' => 50)); ?>
        <?php echo $form->textFieldGroup($model, 'ref'); ?>
        <?php echo $form->textFieldGroup($model, 'refs'); ?>
        <?php echo $form->textFieldGroup($model, 'mines'); ?>
        <?php echo $form->textFieldGroup($model, 'chattime', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'radiotime', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'chatclantime', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'chattimecaves', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'etm', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'buy_submob_info'); ?>
        <?php echo $form->textFieldGroup($model, 'last_wrong_login_cnt'); ?>
        <?php echo $form->dateTimePickerGroup($model, 'last_wrong_login_time'); ?>
        <?php echo $form->dropDownListGroup($model, 'read_news', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да')))); ?>
        <?php echo $form->textFieldGroup($model, 'reg_date'); ?>
        <?php echo $form->textFieldGroup($model, 'wins_quest'); ?>
        <?php echo $form->dateTimePickerGroup($model, 'last_visit_toxic_caves'); ?>
        <?php echo $form->dateTimePickerGroup($model, 'unv_start'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut6">
        <br>
        <?php
        $clans = array(0 => 'Вне клана');
        foreach(CHtml::listData(Clans::model()->findAll(array('order' => 'name')), 'id', 'name') as $id => $name) {
          $clans[$id] = $name;
        }
        echo $form->dropDownListGroup($model, 'clan', array('widgetOptions' => array('data' => $clans)));
        ?>
        <?php echo $form->textFieldGroup($model, 'ClanThresholdTime'); ?>
        <?php echo $form->dropDownListGroup($model, 'PreviousClanId', array('widgetOptions' => array('data' => $clans))); ?>
        <?php echo $form->dropDownListGroup($model, 'clan_money', array('widgetOptions' => array('data' => array('N' => 'Нет', 'Y' => 'Да')))); ?>
        <?php echo $form->dropDownListGroup($model, 'clan_store', array('widgetOptions' => array('data' => array('N' => 'Нет', 'Y' => 'Да')))); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut7">
        <br>
        <?php echo $form->textFieldGroup($model, 'ops'); ?>
        <?php echo $form->textFieldGroup($model, 'alethite'); ?>
        <?php echo $form->textFieldGroup($model, 'burelia'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut8">
        <br>
        <?php echo $form->textFieldGroup($model, 'pleft'); ?>
        <?php echo $form->textFieldGroup($model, 'impl'); ?>
        <?php echo $form->dropDownListGroup($model, 'logme', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да')))); ?>
        <?php echo $form->textFieldGroup($model, 'mail_limit'); ?>
        <?php echo $form->dropDownListGroup($model, 'buy_map_pustosh', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да')))); ?>
        <?php echo $form->dropDownListGroup($model, 'unv', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да')))); ?>
        <?php echo $form->dropDownListGroup($model->getMetaModel('exchange_mailing'), 'value', array(
          'widgetOptions' => array(
            'data' => array('0' => 'Нет', '1' => 'Да'),
            'prompt'=>'Select',
            'htmlOptions' => array('name' => 'Players[meta][exchange_mailing]'))
        )); ?>
        <?php echo $form->dropDownListGroup($model, 'unv_ban', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да')))); ?>
        <?php echo $form->dropDownListGroup($model->getMetaModel('lisa'), 'value', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да'), 'htmlOptions' => array('name' => 'Players[meta][lisa]')))); ?>
        <?php echo $form->dropDownListGroup($model, 'email_confirmed', array('widgetOptions' => array('data' => array('No' => 'Нет', 'Yes' => 'Да')))); ?>
        <?php echo $form->dropDownListGroup($model, 'hidde_in_records', array('widgetOptions' => array('data' => array('0' => 'Нет', '1' => 'Да')))); ?>
        <?php echo $form->dropDownListGroup($model, 'nomail', array('widgetOptions' => array('data' => array('0' => 'Подписан', '1' => 'Не подписан')))); ?>
        <?php echo $form->dropDownListGroup($model, 'automove', array('widgetOptions' => array('data' => array('0' => 'Отключен', '1' => 'Включен')))); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut9">
        <br>
        <?php echo $form->dropDownListGroup($model, 'q_gauss', array('widgetOptions' => array('data' => array('N' => 'Нет', 'Y' => 'Да')))); ?>
        <?php echo $form->textFieldGroup($model, 'rq_ts_d', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->textFieldGroup($model, 'rq_id_d'); ?>
        <?php echo $form->textFieldGroup($model, 'rq_ts_h'); ?>
        <?php echo $form->textFieldGroup($model, 'rq_id_h'); ?>
        <?php echo $form->dropDownListGroup($model, 'rq_ok', array('widgetOptions' => array('data' => array('N' => 'Нет', 'Y' => 'Да')))); ?>
        <?php echo $form->textFieldGroup($model, 'rq_st'); ?>
        <?php echo $form->textFieldGroup($model, 'rq_drop1_st'); ?>
        <?php echo $form->textFieldGroup($model, 'rq_drop2_st'); ?>
        <?php echo $form->textFieldGroup($model, 'rq_cnt'); ?>
        <?php echo $form->textFieldGroup($model, 'rq_from', array('size' => 4, 'maxlength' => 4)); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut11">
        <br>
        <?php echo $form->dropDownListGroup($model, 'travel_place', array('widgetOptions' => array('data' => array('0' => 'Неизвестно', '/pustosh.php' => 'Пустошь', '/caves.php' => 'Пещеры', '/labyrinth.php' => 'Лабиринт')))); ?>
        <?php echo $form->textFieldGroup($model, 'distance'); ?>
        <?php echo $form->textFieldGroup($model, 'x'); ?>
        <?php echo $form->textFieldGroup($model, 'y'); ?>
        <?php echo $form->textFieldGroup($model, 'labyrinth_y'); ?>
        <?php echo $form->textFieldGroup($model, 'labyrinth_x'); ?>
      </div>
      <div role="tabpanel" class="tab-pane" id="ut12">
          <div id="avatars_block">
              <?php
              foreach ($avatars as $avatar) {
                  echo "<img src='/images/chat/avatars/".$avatar->image."' ".(($chatavatar==$avatar->avatar_id)?'class="active"':'')." alt='".$avatar->avatar_id."' title='".$avatar->avatar_id."' data-id='".$avatar->avatar_id."' />";
              }
              ?>
          </div>
          <input type="hidden" name="user_chat_avatar" id="user_chat_avatar" value="<?php echo $chatavatar; ?>" />
      </div>
    </div>

  </div>

  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
    </div>
  </div>

  <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript("chat_avatar", "
$('#avatars_block img').on('click', function (e) {
    var cur_id = $(this).data('id');
    $('#user_chat_avatar').val(cur_id);
    $('#avatars_block img').removeClass('active');
    $(this).addClass('active');
});
  ", CClientScript::POS_READY);