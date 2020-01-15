<?php
/* @var $this PlayersController */
/* @var $model Players */

$this->breadcrumbs = array(
    'Игроки' => array('index'),
    $model->user.' ['.$model->id.']' => array('update', 'id' => $model->id),
    'Редактирование',
);

$this->menu = array(
  /*	array('label'=>'Добавить игрока', 'url'=>array('create')), */
    array('label' => 'Перечень игроков', 'url' => array('index')),

);

if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics'))) {
  $this->menu[] = array('label' => 'Аналитика', 'url' => Yii::app()->createUrl("admin/players/analytics", array("id" => $model->id)));
}
if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {
  $this->menu[] = array('label' => 'Логи', 'url' => Yii::app()->createUrl("admin/playerLog/index", array("player_id" => $model->id)));
  $this->menu[] = array('label' => 'Внутриигровая почта', 'url' => Yii::app()->createUrl("admin/playerMail/index", array("player_id" => $model->id)));
  $this->menu[] = array('label' => 'Общий чат', 'url' => Yii::app()->createUrl("admin/commonChat/index", array("player_id" => $model->id)));
  $this->menu[] = array('label' => 'Подарки', 'url' => Yii::app()->createUrl("admin/playerPresents2/index", array("player_id" => $model->id)));
}
?>

<h1>Игрок [<?php echo $model->id; ?>] <?= $model->user ?></h1>

<?php $this->widget('bootstrap.widgets.TbAlert'); // Yii::app()->user->getFlash('portCave') ?>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?><li role="presentation" class="active"><a href="#person-editor" aria-controls="person-editor" role="tab" data-toggle="tab">Редактирование</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?><li role="presentation"><a href="#outposts" aria-controls="outposts" role="tab" data-toggle="tab">Дом</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?><li role="presentation"><a href="#effects" aria-controls="effects" role="tab" data-toggle="tab">Временные эффекты</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin')|| Yii::app()->user->checkAccess('user_teleportation'))) {?><li role="presentation" <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('user_teleportation'))) {?>class="active"<?php } ?>><a href="#tele" aria-controls="person-tele" role="tab" data-toggle="tab">Телепортация</a></li><?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?><li role="presentation"><a href="#inventary" aria-controls="inventary" role="tab" data-toggle="tab">Добавить инвентарь</a></li><?php } ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?>
    <div role="tabpanel" class="tab-pane active" id="person-editor">
      <div class="well">
        <?php $this->renderPartial('_form', array('model' => $model,'chatavatar'=>$chatavatar,'chatavatarimage'=>$chatavatarimage, 'avatars'=>$avatars)); ?>
      </div>
    </div>
    <?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?>
      <div role="tabpanel" class="tab-pane" id="effects">
        <div class="well">
          <?php $this->renderPartial('_effects', array('model' => $model)); ?>
        </div>
      </div>
    <?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?>
      <div role="tabpanel" class="tab-pane" id="outposts">
        <div class="well">
          <?php $this->renderPartial('_outposts', array('model' => $model)); ?>
        </div>
      </div>
    <?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('user_teleportation'))) {?>
    <div role="tabpanel" class="tab-pane <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('user_teleportation'))) {?>active<?php } ?>" id="tele">
      <div class="well">
        <div class="form-group row">
          <div class="col-sm-offset-2 col-sm-3">
            <?= CHtml::beginForm(array($this->createUrl('portCave')), 'post', array('class' => 'form-horizontal')) ?>
            <?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
            <?= CHtml::submitButton('Телепортировать в пещеры', array('class' => 'btn btn-primary')) ?>
            <?= CHtml::endForm() ?>
          </div>
          <div class="col-sm-offset-2 col-sm-3">
            <?= CHtml::endForm() ?>
            <?= CHtml::beginForm(array($this->createUrl('portCity')), 'post', array('class' => 'form-horizontal')) ?>
            <?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
            <?= CHtml::submitButton('Телепортировать в город', array('class' => 'btn btn-primary')) ?>
            <?= CHtml::endForm() ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin'))) {?>
    <div role="tabpanel" class="tab-pane" id="inventary">
      <div class="well">
        <?php $this->renderPartial('_inventary', array('model' => $model)); ?>
      </div>
    </div>
    <?php } ?>
  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#count").on('change blur keyup',function(){
      if($(this).val()<1) {
        $(this).parents('form').find("input[type='submit']").attr('disabled', 'disabled');
      } else {
        $(this).parents('form').find("input[type='submit']").removeAttr('disabled');
      }
    });
  });
</script>


