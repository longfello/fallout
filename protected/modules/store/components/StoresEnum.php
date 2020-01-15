<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class StoresEnum extends Enum {
  const BANK_STORE  = 'bstore';
  const CLAN_STORE  = 'cstore';
  const TOXIC_STORE = 'toxstore';

  static $available = array(
      StoresEnum::BANK_STORE,
      StoresEnum::CLAN_STORE,
      StoresEnum::TOXIC_STORE,
  );

}