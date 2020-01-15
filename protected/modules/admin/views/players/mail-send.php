<?php
/**
 *
 * @var PlayersController $this
 * @var \MailForm $model
 * @var $form TbActiveForm
 */

$ids = $model->getIds();
?>

<div class="well">
  <p>Рассылка <?= count($ids) ?> игрокам.</p>
  <div class="progress progress-sm active">
    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped">
      <span class="sr-only">0%</span>
    </div>
  </div>
  <div class="well log"></div>
  <a href="#" class="btn btn-primary btn-start">Начать рассылку</a>
  <a href="<?= $this->createUrl('mail') ?>" class="btn btn-default btn-stop">Отмена</a>
</div>

<?php
Yii::app()->clientScript->registerScript('mail-send', "
(function(){
  var queue = 100;
  var ids  = ".CJavaScript::encode($ids).";
  var cnt  = ".count($ids).";
  var curr = 0;

  $('.btn-start').on('click', function(e){
    e.preventDefault();
    $(this).attr('disabled', 'disabled');
    sendPart();
  });

  function sendPart(){
    var data = ids.splice(0, queue);
    if (data.length) {
      $.post('/admin/players/mailSend', {ids:data, info: ".CJavaScript::encode($model->attributes)."}, function(retdata){
        $('.log').append('<p>'+retdata+'</p>');
        curr += data.length;
        setPB( 100*curr/cnt );
        sendPart();
      });
    } else {
      $('.log').append('<p>Готово!</p>');
    }
  }

  function setPB(val){
    $('.progress .progress-bar').css('width', val+'%').attr('aria-valuenow', val).find('span').html(val+'%');
    $('.log').html(curr+' писем поставлено в очередь отправки ('+Math.round(100*val)/100+'%)');
  }
})();
", CClientScript::POS_READY);