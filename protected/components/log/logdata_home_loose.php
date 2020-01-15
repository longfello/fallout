<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_home_loose extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name', 'oid', 'gain'
  );
  public $message ='Вы проиграли бой за свой дом. Игрок <a href="view.php?view=%s">%s</a> (id дома %s) забрал у вас <b>%s</b><img src="/images/tokens.png">.';
  public $type = 'home_loose';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name, $oid, $gain){
    $data = array(
      'player_id' => $player_id,
      'player_name' => $player_name,
      'oid' => $oid,
      'gain' => $gain
    );
    self::save($to_id, Log::CATEGORY_HOUSE , $data);
  }
}