<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_clan_exit extends logdata_prototype {
  public $fields = array(
    'clan_name'
  );
  public $message ='Вы были изгнаны из клана %s.';
  public $type = 'clan_exit';


  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $clan_name){
    $data = array(
      'clan_name' => $clan_name,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}