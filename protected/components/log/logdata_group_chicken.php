<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_group_chicken extends logdata_prototype {
  public $fields = array(
    'title'
  );
  public $message ='На бой %s не пришел ни один боец. С арены убегают только трусы!';
  public $type = 'group_chicken';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($players_models, $title){
    $data = array(
      'title' => $title,
    );
    foreach ($players_models as $one) {
      self::save($one->id, Log::CATEGORY_PVP , $data);
    }
  }
}