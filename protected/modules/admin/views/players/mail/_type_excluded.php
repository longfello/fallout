<?php
/* @var $model MailForm */
/* @var $form TbActiveForm */
?>

  <div class="form-group">
    <label for="MailForm_title" class="control-label required">
      Логин игрока
    </label>
    <input type="text" id="excludedAC" placeholder="Введите логин игрока" class="form-control">
  </div>
  <div class="excludedACData">

  </div>
  <div class="excludedACDataTemplate hidden">
    <div class="player">
      <?= $form->hiddenField($model, 'excluded_ids[]', array('value' => 0)) ?>
      <span class="login"></span>
      <a href="#" class="remove">x</a>
    </div>
  </div>
<?php

Yii::app()->clientScript->registerScript("excludedAC", "

  $('#excludedAC').autocomplete({
    source: function( request, response ) {
      $.ajax({
        url: '/admin/players/ac',
        dataType: 'json',
        data: {
          q: request.term
        },
        success: function( data ) {
          response( data );
        }
      });
    },
    minLength: 3,
    select: function( event, ui ) {
      var input = $('.excludedACDataTemplate .player').clone();
      $('.login', input).html(ui.item.label);
      $('input', input).val(ui.item.value);
      $('a.remove', input).on('click', function(e){
        e.preventDefault();
        $(this).parents('.player').remove();
      });
      $('.excludedACData').append(input);
      $(this).val(''); return false;
    }
  });

", CClientScript::POS_READY);
