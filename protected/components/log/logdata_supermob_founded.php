<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_supermob_founded extends logdata_prototype {
  public $fields = array(
    'distance'
  );
  public $message ="Супермутант был замечен в районе, от которого до Water City %s.";
  public $type = 'supermob_founded';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $distance){
    $data = array(
      'distance' => $distance,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}