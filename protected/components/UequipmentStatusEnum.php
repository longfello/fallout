<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.07.15
 * Time: 13:55
 */
class UequipmentStatusEnum extends Enum {
  const UNEQUIPPED = 'U';
  const EQUIPPED   = 'E';

  static $available = array(
      UequipmentStatusEnum::UNEQUIPPED,
      UequipmentStatusEnum::EQUIPPED,
  );
}