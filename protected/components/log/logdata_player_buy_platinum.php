<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_player_buy_platinum extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name', 'cost'
  );
  public $message ='<b><a href="/view.php?view=%s">%s</a></b> купил ваши крышки. <b>%s</b> золота переведено на ваш счёт в банке.';
  public $type = 'player_buy_platinum';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name, $cost){
    $data = array(
      'player_id' => $player_id,
      'player_name' => $player_name,
      'cost' => $cost
    );
    self::save($to_id, Log::CATEGORY_MARKET , $data);
  }
}