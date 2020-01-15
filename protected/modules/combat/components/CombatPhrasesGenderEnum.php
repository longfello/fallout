<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class CombatPhrasesGenderEnum extends Enum {
  const MALE   = 'male';
  const FEMALE = 'female';

  static $available = array(
    CombatPhrasesGenderEnum::MALE,
    CombatPhrasesGenderEnum::FEMALE,
  );
}