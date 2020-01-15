<?php $player->max_hp = $player->max_hp?$player->max_hp:1; ?>

<div class="fighter fighter-<?= $num ?> player-<?= $player->id ?>">
  <div class="defend">
    <h3><?= t::get('Атаковать:') ?></h3>
    <input id="cfirst5" type="radio" name="attack" class="radio-attack" value="<?=CombatRoundEnum::HEAD?>" />
    <label for="cfirst5"><?= t::get('Голова') ?></label>

    <input id="cfirst6" type="radio" name="attack" class="radio-attack" value="<?=CombatRoundEnum::HAND?>" />
    <label for="cfirst6"><?= t::get('Руки') ?></label>

    <input id="cfirst7" type="radio" name="attack" class="radio-attack" value="<?=CombatRoundEnum::BODY?>" />
    <label for="cfirst7"><?= t::get('Туловище') ?></label>

    <input id="cfirst8" type="radio" name="attack" class="radio-attack" value="<?=CombatRoundEnum::FOOT?>" />
    <label for="cfirst8"><?= t::get('Ноги') ?></label>
  </div>
  <div class="avatar-fighter">
    <?php gamePrintPlayerCore($player); ?>
    <div class="live-line-wraper">
      <div class="live-line">
        <div class="red-line" title="<?= "{$player->hp}/{$player->max_hp}"?>" style="width:<?= round(100*$player->hp/$player->max_hp) ?>%" data-hp="<?= $player->max_hp ?>"></div>
      </div>
    </div>
  </div>

</div>
