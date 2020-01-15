<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_stop_list_text extends logdata_prototype {
  public $fields = array('player_name');
  public $message ='';
  public $type = 'stop_list_text';

  public function __add($to_id, $player_name){
    $data = array('player_name' => $player_name);
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function render($data){
    return t::get("stoplist-text-1", $data['player_name']);
  }
}