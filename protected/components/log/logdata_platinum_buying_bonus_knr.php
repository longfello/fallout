<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_platinum_buying_bonus_knr extends logdata_prototype {
  public $fields = array('dollars', 'caps');
  public $message ='Использовано %s $ НКР - бонус %s крышек.';
  public $type = 'platinum_buying_bonus_knr';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $dollars, $caps){
    self::save($to_id, Log::CATEGORY_ETC , array('dollars' => $dollars, 'caps' => $caps));
  }
}