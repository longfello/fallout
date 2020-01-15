<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_buy_item extends logdata_prototype {
  public $fields = array(
    'item', 'cost'
  );
  public $message ='<b><a href="/view.php?view=4">Администрация</a></b> купила ваш предмет %s. <b>%s</b> золота добавлено на ваш счёт в банке.';
  public $type = 'admin_buy_item';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }
    
  public function __add($to_id, $item_id, $cost){
    $data = array(
      'item' => $item_id,
      'cost' => $cost
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