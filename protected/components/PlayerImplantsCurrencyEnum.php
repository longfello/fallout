<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class PlayerImplantsCurrencyEnum extends Enum {
  const GOLD     = 'gold';
  const PLATINUM = 'platinum';

  static $available = array(
    self::GOLD,
    self::PLATINUM,
  );
}