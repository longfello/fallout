<?php
/**
 *
 * @var Players $model
 * @var Players $mults
 */
?>
<div class="box box-primary">
  <div class="box-header" style="cursor: move;">
    <i class="ion ion-search"></i>
    <h3 class="box-title">Приглашенные игроки</h3>
  </div><!-- /.box-header -->
  <div class="box-body">
    <ul class="todo-list ui-sortable">
      <?php foreach($refs as $ref) { ?>
        <li>
          <span class="text"><?= $ref->user ?></span>
          <!-- Emphasis label -->
          <small class="label label-success" title="Последний логин"><i class="fa fa-clock-o"></i> <?= Tool::dateDiff(new DateTime("@{$ref->etm}")); ?></small>
          <?php
            if ($bans = Ban::checkPlayer(false, $ref)) {
              foreach ($bans as $bot) { ?>
                <span class="label label-info" title="До когда действительна блокировка"><i class="fa fa-clock-o"></i> <?= $bot->until?date('d.m.Y H:i:s'):"Навсегда" ?> </span>
                <?php if ($bot->site)    { ?> <span class="label label-danger"><i class="fa fa-crosshairs"></i> Сайт </span> <?php } ?>
                <?php if ($bot->advert)  { ?> <span class="label label-danger"><i class="fa fa-crosshairs"></i> Объявления </span> <?php } ?>
                <?php if ($bot->chat)    { ?> <span class="label label-danger"><i class="fa fa-crosshairs"></i> Чат </span> <?php } ?>
                <?php
                if ($bot->block_ip){
                  $ips = array();
                  foreach ($bot->ips as $one) {
                    $ips[] = long2ip($one->ip);
                  }
                  ?>
                  <span class="label label-warning"><i class="fa fa-asterisk"></i> IP <?= implode(',', $ips) ?> </span> <?php
                } ?>
              <?php }
            }
          ?>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            <a href="<?= $this->createUrl('analytics', array('id' => $ref->id)) ?>"><i class="fa fa-crosshairs"></i></a>
            <a href="<?= $this->createUrl('update', array('id' => $ref->id)) ?>"><i class="fa fa-edit"></i></a>
          </div>
        </li>
      <?php } ?>
    </ul>
    <?php  if (count($refs) == 0) { ?>
      <div class="alert alert-info">
        <b>Игрок не имеет рефералов.</b>
      </div>
    <?php  } ?>
  </div><!-- /.box-body -->
</div>