<?php $player->max_hp = $player->max_hp?$player->max_hp:1; ?>

<div class="fighter fighter-<?= $num ?> player-<?= $player->id ?>">
  <div class="avatar-fighter">
    <?php gamePrintPlayerCore($player); ?>
    <div class="live-line-wraper">
      <div class="live-line">
        <div class="red-line" title="<?= "{$player->hp}/{$player->max_hp}"?>" style="width:<?= round(100*$player->hp/$player->max_hp) ?>%" data-hp="<?= $player->max_hp ?>"></div>
      </div>
    </div>
  </div>
  <div class="defend">
    <h3><?= t::get('Защищать:') ?></h3>
    <input id="cfirst" type="radio" name="def" class="radio-def" value="<?=CombatRoundEnum::HEAD?>"/>
    <label for="cfirst"><?= t::get('Голова') ?></label>

    <input id="cfirst2" type="radio" name="def" class="radio-def" value="<?=CombatRoundEnum::HAND?>" />
    <label for="cfirst2"><?= t::get('Руки') ?></label>

    <input id="cfirst3" type="radio" name="def" class="radio-def" value="<?=CombatRoundEnum::BODY?>" />
    <label for="cfirst3"><?= t::get('Туловище') ?></label>

    <input id="cfirst4" type="radio" name="def" class="radio-def" value="<?=CombatRoundEnum::FOOT?>" />
    <label for="cfirst4"><?= t::get('Ноги') ?></label>
  </div>
</div>
