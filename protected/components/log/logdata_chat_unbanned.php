<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_chat_unbanned extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name'
  );
  public $message ='<b><a href="%s">%s</a></b> снял с вас бан в чате.';
  public $type = 'chat_unbanned';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name){
    $data = array(
      'player_id'   => $player_id,
      'player_name' => $player_name
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}