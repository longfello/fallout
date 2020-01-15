<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_broken_items extends logdata_prototype {
  public $fields = array(
    'ids'
  );
  public $message ='Сломанные вещи: %s.';
  public $type = 'broken_items';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $ids){
    $data = array(
      'ids' => $ids,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }

  public function render($data){
    if (isset($data['ids'])){
      $names = array();
      foreach ($data['ids'] as $item_id){
        $names[] = t::getDb('name', 'equipment', 'id', $item_id);
      }
      $names = implode(', ', $names);
      return t::get($this->message, array($names));
    } else return '';
  }
}