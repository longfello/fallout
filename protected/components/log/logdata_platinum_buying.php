<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_platinum_buying extends logdata_prototype {
  public $fields = array('amount');
  public $message ='Вы купили <b>%s</b> <img src="images/platinum.png" border="0">';
  public $type = 'platinum_buying';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $amount){
    $data = array('amount' => $amount);
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}