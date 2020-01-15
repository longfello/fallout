<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_you_loose_monster extends logdata_prototype {
  public $fields = array(
    'monster_id'
  );
  public $message ='Вас победил монстр <b>%s</b>.';
  public $type = 'you_loose_monster';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $monster_id){
    $data = array(
      'monster_id'      => $monster_id
    );
    self::save($to_id, Log::CATEGORY_WASTELAND, $data);
  }
  
  public function render($data){
    $name = t::getDb('name', 'npc', 'id', $data['monster_id']);
    return parent::render(array('monster_id' => $name));
  }
}