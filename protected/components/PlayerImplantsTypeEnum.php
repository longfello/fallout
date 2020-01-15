<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class PlayerImplantsTypeEnum extends Enum {
  const MAX_ENERGY = 'max_energy';
  const STRENGTH = 'strength';
  const AGILITY = 'agility';
  const DEFENSE = 'defense';
  const MAX_HP = 'max_hp';

  static $available = array(
    self::MAX_ENERGY,
    self::STRENGTH,
    self::AGILITY,
    self::DEFENSE,
    self::MAX_HP
  );
}