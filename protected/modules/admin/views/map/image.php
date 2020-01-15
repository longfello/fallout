<?php
/**
 *
 * @var MapController $this
 * @var int $cnt
 */

?>

<div class="well">
  <p>Раскройка картинки по координатам.</p>
  <div class="progress progress-sm active">
    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped">
      <span class="sr-only">0%</span>
    </div>
  </div>
  <div class="well log"></div>
</div>

<?php
Yii::app()->clientScript->registerScript('mail-send', "
(function(){
  var cnt  = ".$cnt.";

  sendPart();
  function sendPart(){
      $.post('/admin/map/cut', function(data){
        setPB( 100*(cnt-data.left)/cnt );
        if (data.left > 0){
          sendPart();
        } else {
          $('.log').append('<p>Готово!</p>');
          document.location.href = '".$this->createUrl('index')."';
        }
      }, 'json');
  }

  function setPB(val){
    $('.progress .progress-bar').css('width', val+'%').attr('aria-valuenow', val).find('span').html(val+'%');
    $('.log').html('Обработано '+Math.round(10*val)/10+'%');
  }
})();
", CClientScript::POS_READY);