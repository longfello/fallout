<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_chat_banned extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name', 'until'
  );
  public $message ='<b><a href="%s">%s</a></b> забанил вас в чате до <b>%s</b>!';
  public $type = 'chat_banned';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name, $until){
    $data = array(
      'player_id'   => $player_id,
      'player_name' => $player_name,
      'until'       => $until
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}