<?php
/**
 *
 * @var PlayersController $this
 * @var \GiftsForm $model
 */

$ids = $model->getIds();
$kol_players = count($ids);
?>
<div class="well">
  <h3>Будет выдано <?= $kol_players." ".t::plural('игроку|игрокам|игрокам', $kol_players) ?>.</h3>
  <?php if (count($model->item)>1) { ?>
      <div class="panel panel-default">
          <div class="panel-heading"><h4>Предметы</h4></div>
          <div class="panel-body">
              <ul>
                  <?php foreach ($model->item as $key => $item) {
                      if ($item > 0) { ?>
                          <li>
                              <?php
                              $equipment = Equipment::model()->findByPk($item);
                              echo $equipment->name." x ".(isset($model->count[$key]) ? $model->count[$key] : 1)." шт.";
                              ?>
                          </li>
                      <?php }
                  } ?>
              </ul>
          </div>
      </div>
  <?php } ?>
  <br/>
  <?php if ($model->napad>0) echo "<h4>+ ".$model->napad." ".t::plural('нападение|нападения|нападений',$model->napad)."</h4>"; ?>
  <?php if ($model->pohod>0) echo "<h4>+ ".$model->pohod." ".t::plural('походное очко|походных очка|походных очков', $model->pohod)."</h4>"; ?>
  <?php if ($model->pleft>0) echo "<h4>+ ".$model->pleft." ".t::plural('день стимулятора|дня стимулятора|дней стимулятора', $model->pleft)."</h4>"; ?>
  <br/>
  <h4>Текст в логах</h4>
    <div class="panel panel-default">
        <div class="panel-heading">Русский</div>
        <div class="panel-body"><?= $model->text; ?></div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">English</div>
        <div class="panel-body"><?= $model->text_en; ?></div>
    </div>
    <div class="progress progress-sm active">
        <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped">
            <span class="sr-only">0%</span>
        </div>
    </div>
    <div class="well log"></div>
  <a href="#" class="btn btn-primary btn-start">Выдать</a>
  <a href="<?= $this->createUrl('gifts') ?>" class="btn btn-default btn-stop">Отмена</a>
</div>

<?php
Yii::app()->clientScript->registerScript('mail-send', "
(function(){
  var cnt  = ".count($ids).";
  var curr = 0;

  $('.btn-start').on('click', function(e){
    e.preventDefault();
    $(this).attr('disabled', 'disabled');
     $.post('/admin/players/giftsSend', {info: ".CJavaScript::encode($model->attributes)."}, function(retdata){
        curr += parseInt(retdata);
        setPB( 100*curr/cnt );
        $('.btn-stop').html('Вернуться назад');
        $('.log').append('<p>Готово!</p>');
      });
  });

  function setPB(val){
    $('.progress .progress-bar').css('width', val+'%').attr('aria-valuenow', val).find('span').html(val+'%');
    $('.log').html(curr+' игроков добавлено в очередь выдачи ('+Math.round(100*val)/100+'%)');
  }
})();
", CClientScript::POS_READY);