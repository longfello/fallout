<?php
/**
 *
 * @var \Players $model
 */

?>

<?= CHtml::beginForm(array($this->createUrl('addResource')), 'post', array('class' => 'form-horizontal')) ?>
<?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
<div class="form-group">
  <label class="col-sm-2 control-label">Золото:</label>
  <div class="col-sm-10">
    <?= CHtml::numberField('gold', 0, array('class' => 'form-control', 'min'=>0)) ?>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">Крышки:</label>
  <div class="col-sm-10">
    <?= CHtml::numberField('platinum', 0, array('class' => 'form-control', 'min'=>0)) ?>
  </div>
</div>

<div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">
    <?= CHtml::submitButton('Выдать ресурсы', array('class' => 'btn btn-primary')) ?>
  </div>
</div>

<?= CHtml::endForm() ?>

