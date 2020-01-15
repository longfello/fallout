<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_platinum_ref extends logdata_prototype {
  public $fields = array('amount', 'referal_id');
  public $message ='Вам зачисленно <b>%s</b> <img src="/images/platinum.png" border="0"> от вашего реферала <b>ID %s</b>';
  public $type = 'platinum_ref';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $amount, $referal_id){
    $data = array(
      'amount' => $amount,
      'referal_id' => $referal_id,
    );
    self::save($to_id, Log::CATEGORY_ETC, $data);
  }
}