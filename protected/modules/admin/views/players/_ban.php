<?php
/* @var $this PlayersController */
/* @var $model Players */
/* @var $ban Banned */
/* @var $form CActiveForm */
?>
<div class="row">

</div>
<?php
if ($bots) {
  ?>
  <div class="box box-primary">
    <div class="box-header" style="cursor: move;">
      <i class="ion ion-alert-circled"></i>
      <h3 class="box-title">Текущие блокировки игрока</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
      <ul class="todo-list">
        <?php
        foreach ($bots as $bot) {
          ?>
                <li>
                  <!-- todo text -->
                  <!-- Emphasis label -->
                  <span class="text"><?= $bot->reason ?></span>
                  <span class="label label-info" title="До когда действительна блокировка"><i class="fa fa-clock-o"></i> <?= $bot->until?date('d.m.Y H:i:s', strtotime($bot->until)):"Навсегда" ?> </span>
                  <span class="label label-success" title="Дата создания/модератор"><i class="fa fa-clock-o"></i> <?= date('d.m.Y H:i:s', strtotime($bot->created)) ?> / <?= $bot->admin->username; ?></span>
                  <?php if ($bot->site)    { ?>
                    <span class="label <?= ($bot->active_site)?'label-default':'label-danger'; ?>"><i class="fa fa-crosshairs"></i> Сайт<?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_site'))) { ?>&nbsp;<a href="<?= $this->createUrl('toggleBanRule', array('id' => $bot->id, 'type' => 'site')) ?>" title="<?= ($bot->active_site)?'Выключить сайт':'Включить сайт'; ?>"><i class="fa fa-lg <?= ($bot->active_site)?'fa-plus-square-o':'fa-minus-square-o'; ?>"></i></a><?php } ?></span>
                  <?php } ?>
                  <?php if ($bot->advert)  { ?>
                    <span class="label <?= ($bot->active_advert)?'label-default':'label-danger'; ?>"><i class="fa fa-crosshairs"></i> Объявления<?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_advert'))) { ?>&nbsp;<a href="<?= $this->createUrl('toggleBanRule', array('id' => $bot->id, 'type' => 'advert')) ?>" title="<?= ($bot->active_advert)?'Выключить обьявления':'Включить обьявления'; ?>"><i class="fa fa-lg <?= ($bot->active_advert)?'fa-plus-square-o':'fa-minus-square-o'; ?>"></i></a><?php } ?></span>
                  <?php } ?>
                  <?php if ($bot->chat)    { ?>
                    <span class="label <?= ($bot->active_chat)?'label-default':'label-danger'; ?>"><i class="fa fa-crosshairs"></i> Чат<?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_chat'))) { ?>&nbsp;<a href="<?= $this->createUrl('toggleBanRule', array('id' => $bot->id, 'type' => 'chat')) ?>" title="<?= ($bot->active_chat)?'Выключить чат':'Включить чат'; ?>"><i class="fa fa-lg <?= ($bot->active_chat)?'fa-plus-square-o':'fa-minus-square-o'; ?>"></i></a><?php } ?></span>
                  <?php } ?>
                  <?php
                    if ($bot->block_ip){
                      $ips = array();
                      foreach ($bot->ips as $one) {
                        $ips[] = long2ip($one->ip);
                      }
                  ?>
                    <span class="label label-warning"><i class="fa fa-asterisk"></i> IP <?= implode(',', $ips) ?> </span> <?php
                  } ?>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <?php if ((Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) || (Yii::app()->getUser()->id==$bot->admin_id)) { ?><a href="<?= $this->createUrl('editBanRule', array('id' => $bot->id)) ?>" title="Редактировать правило"><i class="fa fa-edit"></i></a><?php } ?>
                    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) { ?><a href="<?= $this->createUrl('deleteBanRule', array('id' => $bot->id)) ?>" title="Удалить правило"><i class="fa fa-trash-o"></i></a><?php } ?>
                  </div>
                  <br/>
                  <span class="text"><i><?= $bot->comment?$bot->comment:'[ без комментария ]' ?></i></span>
                </li>
          <?
        }
        ?>
      </ul>
    </div><!-- /.box-body -->
  </div>
<?php
} else {
  ?>
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <b>Игрок не заблокирован.</b> В базе данных отсутствуют правила непосредственной блокировки игрока. Тем не менее,
    игрок может быть заблокирован по ip-адресу в правилах другого игрока, если они совпадают. В этом случая вы увидете предупреждение, расположенное ниже.
  </div>
  <?php
}
?>

<?php
foreach($blockedByIP as $one) {
  if ($one->player_id != $model->id) {
    if ($one->login!="*** tor ***") {
    $rulePlayer = Players::model()->findByPk($one->player_id);
    ?>
    <div class="alert alert-danger">
      <i class="fa fa-hand-pointer-o"></i>
      <b>Внимание!</b> IP адрес игрока заблокирован правилом для игрока <a href="<?= $this->createUrl('analytics', array('id' => $one->player_id)) ?>" class="btn btn-flat btn-danger btn-mini"><?= $rulePlayer->user ?></a>
    </div>
  <?php
    } else { ?>
      <div class="alert alert-danger">
        <i class="fa fa-hand-pointer-o"></i>
        <b>Внимание!</b> IP адрес игрока заблокирован так как является IP сети TOR
      </div>
    <?php }
  } ?>
<?php } ?>

<div class="box-footer clearfix no-border">
  <a href="<?= $this->createUrl('createBanRule', array('id' => $model->id)) ?>" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Добавить</a>
</div>

<?php if ($old_bots) {?>
  <br/>
  <div class="box box-primary">
    <div class="box-header" style="cursor: move;">
      <i class="ion ion-alert-circled"></i>
      <h3 class="box-title">Истёкшие блокировки игрока</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
      <ul class="todo-list">
        <?php
        foreach ($old_bots as $bot) {
          ?>
          <li>
            <!-- todo text -->
            <!-- Emphasis label -->
            <span class="text"><?= $bot->reason ?></span>
            <span class="label label-info" title="До когда действительна блокировка"><i class="fa fa-clock-o"></i> <?= $bot->until?date('d.m.Y H:i:s', strtotime($bot->until)):"Навсегда" ?> </span>
            <span class="label label-success" title="Дата создания/модератор"><i class="fa fa-clock-o"></i> <?= date('d.m.Y H:i:s', strtotime($bot->created)) ?> / <?= $bot->admin->username; ?></span>
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
            <div class="tools">
              <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) { ?><a href="<?= $this->createUrl('deleteBanRule', array('id' => $bot->id)) ?>" title="Удалить правило"><i class="fa fa-trash-o"></i></a><?php } ?>
            </div>
            <br/>
            <span class="text"><i><?= $bot->comment?$bot->comment:'[ без комментария ]' ?></i></span>
          </li>
          <?
        }
        ?>
      </ul>
    </div><!-- /.box-body -->
  </div>
<?php } ?>