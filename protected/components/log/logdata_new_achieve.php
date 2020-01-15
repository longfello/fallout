<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_new_achieve extends logdata_prototype {
  public $fields = array(
    'achieve_id'
  );
  public $message ='Получено новое достижение: "%s".';
  public $type = 'new_achieve';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $achieve_id){
    $data = array(
      'achieve_id' => $achieve_id,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }

  public function render($data){
    $achieve_name = t::getDb('name', 'rev_achieve', 'id', $data['achieve_id']);
    return t::get($this->message, array($achieve_name));
  }
}