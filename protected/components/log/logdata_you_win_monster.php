<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_you_win_monster extends logdata_prototype {
  public $fields = array(
    'monsters', 'exp', 'gold'
  );
  public $message ='Вы победили <b>%s</b>! Ваш приз: <b>%s</b> опыта и <b>%s</b> <img src="/images/gold.png"> золота.';
  public $type = 'you_win_monster';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $enemies, $expgain, $goldgain){
    $ids      = array();
    foreach($enemies as $one){
      $ids[] = $one->id;
    }

    $data = array(
      'exp'      => $expgain,
      'gold'     => $goldgain,
      'ids'      => $ids
    );
    $this->save($to_id, Log::CATEGORY_WASTELAND, $data);
  }
  
  public function render($data){
    $data['monsters'] = array();
    foreach($data['ids'] as $id) {
      $data['monsters'][] = t::getDb('name', 'npc', 'id', $id);
    }
    $data['monsters'] = implode( $data['monsters'], ", " );

    return parent::render($data);
  }
}