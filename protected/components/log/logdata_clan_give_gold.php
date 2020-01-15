<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_clan_give_gold extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name', 'amount'
  );
  public $message ='<b><a href="/view.php?view=%s">%s</a></b> выдал вам из казны клана %s <img src="/images/gold.png" alt="Золото"> золота.';
  public $type = 'clan_give_gold';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name, $amount){
    $data = array(
      'player_id' => $player_id,
      'player_name' => $player_name,
      'amount' => $amount
    );
    self::save($to_id, Log::CATEGORY_MARKET , $data);
  }
}