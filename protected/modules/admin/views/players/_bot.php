<?php
/**
 *
 * @var \Players $model
 */
?>

<p class="margin">Интервал обновления, секунд</p>
<div class="input-group input-group-sm">
  <select class="form-control bot-interval">
    <option value="2">2</option>
    <option value="5">5</option>
    <option value="10" selected>10</option>
    <option value="20">20</option>
    <option value="30">30</option>
  </select>
    <span class="input-group-btn">
      <button class="btn <?=$botCheckEnabled?"btn-danger":"btn-info" ?> btn-flat bot-action" type="button" data-action="<?= $botCheckEnabled?"stop":"start" ?>"><?= $botCheckEnabled?"Закончить проверку!":"Начать проверку!"?></button>
    </span>
</div>
<div class="clear"></div>
<table class="table table-bordered table-hover table-striped bot-data">
  <thead>
  <tr class="header">
    <th>Время</th>
    <th>Страница</th>
    <th>Жизни</th>
    <th>Энергия</th>
    <th>X</th>
    <th>Y</th>
    <th>ip</th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach ($botData as $one) {
    /** @var $one Antibot */
    ?>
    <tr class="data">
      <td><?= $one->date ?></td>
      <td><?= $one->page ?></td>
      <td><?= $one->hp ?></td>
      <td><?= $one->energy ?></td>
      <td><?= $one->x ?></td>
      <td><?= $one->y ?></td>
      <td><?= $one->ip ?></td>
    </tr>
    <?php
  }
  ?>
  </tbody>
</table>
<div class="clear"></div>

<script type="text/javascript">
  var botCheckRunning = <?= $botCheckEnabled?"true":"false" ?>;
  $(document).ready(function () {
    $('.bot-action').on('click', function () {
      if ($(this).data('action') == 'start') {
        $(this).data('action', 'stop').html('Закончить проверку!').toggleClass('btn-info btn-danger');
        botCheckRunning = true;
        updateBotData('start');
      } else {
        $(this).data('action', 'start').html('Начать проверку!').toggleClass('btn-info btn-danger');
        botCheckRunning = false;
        updateBotData('stop');
      }
    });
    updateBotData();
  });

  function updateBotData(action) {
    $.post('/admin/players/bot?id=<?=$model->id?>', {action: action},function (data) {
      if (data) {
        $('.bot-data tbody *').remove();
        $('.bot-data tbody').prepend(data);
      }
    });
    if (botCheckRunning) {
      setTimeout(updateBotData, 1000 * $('.bot-interval').val())
    }
  }
</script>