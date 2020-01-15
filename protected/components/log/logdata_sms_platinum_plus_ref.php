<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_sms_platinum_plus_ref extends logdata_prototype {
  public $fields = array(
    'count', 'player_id'
  );
  public $message ='Вам зачисленно <b>%s</b> <img src="/images/platinum.png" border="0"> от вашего реферала <b>ID %s</b>';
  public $type = 'sms_platinum_plus_ref';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $count, $player_id){
    $data = array(
      'count' => $count,
      'player_id' => $player_id
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}