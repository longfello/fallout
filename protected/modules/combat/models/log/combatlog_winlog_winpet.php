<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_winlog_winpet extends combatlog_prototype{
  public $type = __CLASS__;

  public $message ='%s %s <br>';
  public $fields = array('name', 'exp', 'isLevelUp');

  public static function add(){
    $args = func_get_args();
    $model = new self();
    return call_user_func_array(array($model, '__add'), $args);
  }
  
  public function __add($name, $exp, $isLevelUp){
    $data = array(
      'name'      => $name,
      'exp'       => $exp,
      'isLevelUp' => $isLevelUp,
      'type'      => $this->type
    );
    return $data;
  }

  public function render($data) {
    $__data = array(
      'pre_text'   => t::get("Его любимый питомец <b>%s</b> получает <b>%s</b> опыта", array($data['name'], $data['exp'])),
      'post_text'  => $data['isLevelUp']?t::get(" и поднялся на новый уровень"):"",
    );

    return vsprintf($this->message, $__data);
  }

}