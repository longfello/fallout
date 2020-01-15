<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_clan_give_item extends logdata_prototype {
  public $fields = array(
    'player_id', 'player_name', 'quantity', 'item'
  );
  public $message ="<b><a href='/view.php?view=%s'>%s</a></b> выдал вам из кладовой клана <b>%s</b>x <b>%s</b>.";
  public $type = 'clan_give_item';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $player_id, $player_name, $quantity, $item_id){
    $data = array(
      'player_id' => $player_id,
      'player_name' => $player_name,
      'quantity' => $quantity,
      'item' => $item_id
    );
    self::save($to_id, Log::CATEGORY_MARKET , $data);
  }
  
  public function render($data) {
    $model = Equipment::model()->findByPk($data['item']);
    /** @var $model Equipment */
    $data['item'] = $model->getML('name');
    return parent::render($data);
  }
}