<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class CombatPhrasesTypeEnum extends Enum {
  const WIN   = 'win';
  const LOOSE = 'loose';
  const BATTLE = 'battle';

  static $available = array(
    CombatPhrasesTypeEnum::WIN,
    CombatPhrasesTypeEnum::LOOSE,
    CombatPhrasesTypeEnum::BATTLE,
  );
}