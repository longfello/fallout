<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_player_buy_item extends logdata_prototype {
  public $fields = array(
    'link', 'player_name', 'item', 'quantity', 'cost'
  );
  public $message ='<b><a href="%s">%s</a></b> купил ваш предмет %s в количестве %s. <b>%s</b> золота добавлено на ваш счёт в банке.';
  public $type = 'player_buy_item';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_link, $player_name, $item_id, $quantity, $cost){
    $data = array(
      'link' => $player_link,
      'player_name' => $player_name,
      'item' => $item_id,
      'quantity' => $quantity,
      'cost' => $cost
    );
    self::save($to_id, Log::CATEGORY_MARKET , $data);
  }
  
  public function render($data) {
    $model = Equipment::model()->findByPk($data['item']);
    /** @var $model Equipment */
    $data['item'] = $model?$model->getML('name'):'deleted item';
    return parent::render($data);
  }
}