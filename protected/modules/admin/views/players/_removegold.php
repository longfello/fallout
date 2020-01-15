<?php
/**
 *
 * @var \Players $model
 */

?>

<?= CHtml::beginForm(array($this->createUrl('removeResource')), 'post', array('class' => 'form-horizontal')) ?>
<?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
<div class="form-group">
  <label class="col-sm-2 control-label">Золото:</label>
  <div class="col-sm-10">
    <?= CHtml::numberField('gold', 0, array('class' => 'form-control', 'min'=>0)) ?>
    <span class="help-block"><span class="gold_inv"><?= $model->gold; ?></span><img src="/images/gold.png" /> на руках </span>
    <span class="help-block"><span class="gold_bank"><?= $model->bank; ?></span><img src="/images/gold.png" /> в банке </span>
    <span class="help-block"><span class="gold_cave"><?= $model->getToxicGold(); ?></span><img src="/images/gold.png" /> в банке токсических пещер</span>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">Крышки:</label>
  <div class="col-sm-10">
    <?= CHtml::numberField('platinum', 0, array('class' => 'form-control', 'min'=>0)) ?>
    <span class="help-block"><span class="platinum"><?= $model->platinum; ?></span><img src="/images/platinum.png" /></span>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">Вода:</label>
  <div class="col-sm-10">
    <?= CHtml::numberField('water', 0, array('class' => 'form-control', 'min'=>0)) ?>
    <span class="help-block"><span class="water"><?= $model->getWater(); ?></span><img src="/images/tokens.png" /></span>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">Изъять:</label>
  <div class="col-sm-10">
    <?php
    $place = array(
        'hand' => 'Из инвентаря',
        'bank' => 'Из банка (* только золото)',
        'toxbank' => 'Из банка токсических пещер (* только золото)',
    );
    ?>
    <?= CHtml::dropDownList('place', '', $place, array('class' => 'form-control')) ?>
  </div>
</div>
<div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">
    <?= CHtml::submitButton('Изъять ресурсы', array('class' => 'btn btn-primary')) ?>
  </div>
</div>

<?= CHtml::endForm() ?>

