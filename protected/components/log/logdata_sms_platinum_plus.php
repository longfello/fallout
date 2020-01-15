<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_sms_platinum_plus extends logdata_prototype {
  public $fields = array(
    'count'
  );
  public $message ='SMS: Крышек начислено: <b>%s</b>';
  public $type = 'sms_platinum_plus';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $count){
    $data = array(
      'count' => $count,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}