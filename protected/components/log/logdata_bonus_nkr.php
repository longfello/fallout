<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_bonus_nkr extends logdata_prototype {
  public $fields = array('mobs', 'dollars');
  public $message ='За уничтожение %s мобов получено бонусов: %s $ НКР';
  public $type = 'bonus_nkr';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $mobs, $dollars){
    self::save($to_id, Log::CATEGORY_ETC , array('mobs' => $mobs, 'dollars' => $dollars));
  }
}