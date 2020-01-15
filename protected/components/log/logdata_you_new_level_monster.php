<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_you_new_level_monster extends logdata_prototype {
  public $fields = array(
    'monsters_ids'
  );
  public $message ='Во время боя с <b>%s</b>, Вы перешли на новый уровень.';
  public $type = 'you_new_level_monster';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $monsters_ids){
    $data = array(
      'monsters_ids' => $monsters_ids
    );
    self::save($to_id, Log::CATEGORY_WASTELAND , $data);
  }

  public function render($data){
    $monsters = array();
    if (!isset($data['monsters_ids'])) {
      trigger_error('Field data not founded: monsters_ids');
    } else {
      foreach ($data['monsters_ids'] as $id){
        $monsters[] = t::getDb('name', 'npc', 'id', $id);
      }
    }

    return t::get($this->message, implode(', ', $monsters));
  }

}