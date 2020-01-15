<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_auction_begin extends logdata_prototype {
  public $fields = array(
    'id'
  );
  public $message ='Начат <a href="/idauction.php">аукцион</a>! Лот - ID %s';
  public $type = 'auction_begin';


  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $id){
    $data = array(
      'id' => $id,
    );
    self::save($player_id, Log::CATEGORY_ETC , $data);
  }
}