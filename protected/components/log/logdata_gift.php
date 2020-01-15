<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_gift extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name', 'gift_name'
  );
  public $message ='<b><a href=view.php?view=%s>%s</a></b> подарил вам %s.';
  public $type = 'gift';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name, $gift_name){
    $data = array(
      'player_id' => $player_id,
      'player_name' => $player_name,
      'gift_name' => $gift_name,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }

  public function render($data) {
    if ($data['player_id']==0) {
      $data['player_id'] = '';
      $data['player_name'] = '';
      $this->message = '<b>Администрация</b> подарила Вам %s%s%s.';
    }

    return parent::render($data);
  }
}