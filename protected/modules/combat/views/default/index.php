<?php
/**
 * @var $this DefaultController
 * @var $combat Combat
 */
?>
<div class="combat" id="combat">
    <div class="combat-main">
        <div class="combat-fighters">
            <div class="make-choice <?= $isAutomove?"hidden":"" ?>">
                <p class="lines-bg">
                  <?= t::get('Сделайте выбор') ?>
                  <span class="timer" data-time="<?= $isAutomove?999:Combat::getRoundElapsedSeconds($combat->combat_id); ?>" data-move="<?= $isAutomove?-1:30 ?>"></span><?= t::get('с'); ?>
                </p>
            </div>
            <?php if (!$isAutomove) { ?>
              <button class="lines-bg btn-autofight" type="button" ><?= t::get('Автобой') ?></button>
            <?php } ?>
            <div class="fighters">
                <?php $this->renderPartial('_index_fighter_defend', array('player' => $player, 'num' => (($p1->id == Yii::app()->stat->id)?1:2))) ?>
                <div class="fight-with">
                  <p class="hero-name"><a href="/view.php?view=<?= $p1->id ?>" target="_blank"><?= $p1->user ?></a></p>
                  <p><?= t::get('против') ?></p>
                  <p class="hero-name"><a href="/view.php?view=<?= $p2->id ?>" target="_blank"><?= $p2->user ?></a></p>
                </div>
                <?php $this->renderPartial('_index_fighter_attack', array('player' => $enemy, 'num' => (($p1->id == Yii::app()->stat->id)?2:1))) ?>
            </div>
          <?php if ($isAutomove) { ?>
            <p class="lines-bg hidden">
              <button type="button" class="btn-go"><?= t::get('В бой') ?></button>
            </p>
            <p class="lines-nobg lines-bg">
              <button class="lines-bg btn-autofight" disabled="disabled" type="button" ><?= t::get('Автобой') ?></button>
            </p>
          <?php } else { ?>
            <p class="lines-bg">
              <button type="button" class="btn-go"><?= t::get('В бой') ?></button>
            </p>
          <?php } ?>
        </div>
        <div class="fight-log"></div>
    </div>
</div>