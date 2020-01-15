<?php
/**
 *
 * @var PlayersController $this
 * @var Players $model
 * @var Banned $ban
 */

if (Yii::app()->authManager && Yii::app()->user->checkAccess('admin')) {
  $this->breadcrumbs = array(
      'Игроки' => array('index'),
      $model->user => array('update', 'id' => $model->id),
      'Аналитика',
  );
} else {
  $this->breadcrumbs = array(
      'Игроки' => array('index'),
      $model->user,
      'Аналитика',
  );
}


$this->menu = array(
  array('label' => 'Перечень игроков', 'url' => array('index')),
);

if (Yii::app()->user->checkAccess('admin')) {
  $this->menu[] = array('label' => 'Редактирование игрока', 'url' => Yii::app()->createUrl("admin/players/update", array("id" => $model->id)));
}

if (Yii::app()->authManager && (Yii::app()->user->checkAccess('user_teleportation'))) {
  $this->menu[] = array('label' => 'Панель телепортации', 'url' => Yii::app()->createUrl("admin/players/update", array("id" => $model->id)));
}


?>

<h1>Игрок [<?php echo $model->id; ?>] <?= $model->user ?></h1>

<?php $this->widget('bootstrap.widgets.TbAlert'); // Yii::app()->user->getFlash('portCave') ?>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_advert') || Yii::app()->user->checkAccess('analytics_ban_chat') || Yii::app()->user->checkAccess('analytics_ban_site'))) {?> <li role="presentation" class="active"><a href="#ban" aria-controls="ban" role="tab" data-toggle="tab">Бан</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin')  || Yii::app()->user->checkAccess('analytics_mult'))) {?> <li role="presentation"><a href="#mults" aria-controls="mults" role="tab" data-toggle="tab">Мульты игрока</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?> <li role="presentation"><a href="#refs" aria-controls="refs" role="tab" data-toggle="tab">Рефералы игрока</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?> <li role="presentation"><a href="#bot" aria-controls="bot" role="tab" data-toggle="tab">Контроль ботов</a></li><?php } ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_advert') || Yii::app()->user->checkAccess('analytics_ban_chat') || Yii::app()->user->checkAccess('analytics_ban_site'))) {?>
      <div role="tabpanel" class="tab-pane active" id="ban">
        <div class="well">
          <?php $this->renderPartial('_ban', array('model' => $model, 'blockedByIP' => $blockedByIP, 'bots' => $bots, 'old_bots' => $old_bots)); ?>
        </div>
      </div>
    <?php }
    if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {
      ?>
      <div role="tabpanel" class="tab-pane" id="bot">
        <div class="well">
          <?php $this->renderPartial('_bot', array('model' => $model, 'botData' => $botData, 'botCheckEnabled' => $botCheckEnabled)); ?>
        </div>
      </div>
      <?php
    }
    if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin')  || Yii::app()->user->checkAccess('analytics_mult'))) {
      ?>
      <div role="tabpanel" class="tab-pane" id="mults">
        <div class="well">
          <?php $this->renderPartial('_mults', array('model' => $model, 'mults' => $mults)); ?>
        </div>
      </div>
      <?php
    }
    if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {
    ?>
      <div role="tabpanel" class="tab-pane" id="refs">
        <div class="well">
          <?php $this->renderPartial('_refs', array('model' => $model, 'refs' => $refs)); ?>
        </div>
      </div>
    <?php } ?>
  </div>

</div>


