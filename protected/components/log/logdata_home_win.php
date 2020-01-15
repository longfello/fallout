<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_home_win extends logdata_prototype {
  public $fields = array(
    'oid', 'player_id', 'player_name', 'gain'
  );
  public $message ='Вы победили в бою против дома (id дома %s) и ограбили <a href="view.php?view=%s">%s</a> на <b>%s</b> <img src="/images/tokens.png">';
  public $type = 'home_win';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $oid, $player_id, $player_name, $gain){
    $data = array(
      'oid' => $oid,
      'player_id' => $player_id,
      'player_name' => $player_name,
      'gain' => $gain
    );
    self::save($to_id, Log::CATEGORY_HOUSE , $data);
  }
}