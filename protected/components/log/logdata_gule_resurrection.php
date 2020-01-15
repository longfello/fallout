<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_gule_resurrection extends logdata_prototype {
  public $message ='Операция прошла успешно. Теперь Вы - полноценный гуль!';
  public $type = 'gule_resurrection';


  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id){
    self::save($to_id, Log::CATEGORY_ETC, []);
  }
}