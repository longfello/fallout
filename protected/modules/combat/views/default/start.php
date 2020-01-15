<?php
/* @var $this DefaultController */
?>
<div class="description">
    <? if (Yii::app()->user->hasFlash('error')) : ?> <?= Window::error(Yii::app()->user->getFlash('error')) ?>  <? endif ?>
    <table class="tab table">
        <tr><td colspan="2" align="center"><?= Yii::app()->stat->user ?></td></tr>
        <tr><td><?= t::get('Здоровье'); ?></td><td><?= Yii::app()->stat->hp ?></td></tr>
        <tr><td><?= t::get('Сила') ?></td><td><?= Yii::app()->stat->strength ?></td></tr>
        <tr><td><?= t::get('Ловкость') ?></td><td><?= Yii::app()->stat->agility ?></td></tr>
        <tr><td><?= t::get('Защита') ?></td><td><?= Yii::app()->stat->defense ?></td></tr>
        <tr><td><?= t::get('Энергия'); ?></td><td><?= Yii::app()->stat->energy ?></td></tr>
    </table>
    <table class="tab table">
        <tr><td colspan="2" align="center"><?= $enemy->user ?></td></tr>
        <tr><td><?= t::get('Здоровье'); ?></td><td><?= $enemy->hp ?></td></tr>
        <tr><td><?= t::get('Сила') ?></td><td><?= $enemy->strength ?></td></tr>
        <tr><td><?= t::get('Ловкость') ?></td><td><?= $enemy->agility ?></td></tr>
        <tr><td><?= t::get('Защита') ?></td><td><?= $enemy->defense ?></td></tr>
        <tr><td><?= t::get('Энергия'); ?></td><td><?= $enemy->energy ?></td></tr>
    </table>
    <div class="clear"></div>
    <form action="<?= $this->createUrl('default/go') ?>" method="post">
      <input type="hidden" value="<?= $enemy->id ?>" name="player_2">
      <button id="startCombat" type="submit"><?= t::get('Начать бой') ?></button>
    </form>
</div>
