<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class CombatRoundEnum extends Enum {
  const HEAD = 'head';
  const HAND = 'hand';
  const BODY = 'body';
  const FOOT = 'foot';

  static $available = array(
    CombatRoundEnum::FOOT,
    CombatRoundEnum::BODY,
    CombatRoundEnum::HAND,
    CombatRoundEnum::HEAD,
  );

  public static function correctOrRandom($value = false){
    if (!in_array($value, CombatRoundEnum::$available)) {
      $values = CombatRoundEnum::$available;
      $value = $values[array_rand($values)];
    }
    return $value;
  }

  public static function getName($const) {
    switch($const){
      case self::HEAD: return t::get('в голову');    break;
      case self::HAND: return t::get('по рукам');    break;
      case self::BODY: return t::get('по туловищу'); break;
      case self::FOOT: return t::get('по ногам');    break;
    }
    return $const;
  }
}