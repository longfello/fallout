<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_block extends combatlog_prototype{
  public $type = __CLASS__;
  public $message ='%s ударил %s. %s поставил блок. Урон: 1. (Осталось: %s)';
  public $fields = array('attacker_id', 'defender_id', 'hp');

  public static function add(){
    $args = func_get_args();
    $model = new self();
    return call_user_func_array(array($model, '__add'), $args);
  }
  
  public function __add($battleID, $roundID, $data){
    self::save($battleID, $roundID, $data);
  }

  public function render($data) {
    $attacker = Players::model()->findByPk($data['attacker_id']);
    $defender = Players::model()->findByPk($data['defender_id']);

    $__data = array();
    $__data['attacker'] = '??';
    $__data['defender'] = '??';
    $__data['defender2'] = '??';
    $__data['hp'] = $data['hp'];
    if ($attacker && $defender) {
      $__data['attacker'] = $attacker->getLinkAndName();
      $__data['defender'] = $defender->getLinkAndName();
      $__data['defender2'] = $defender->getLinkAndName();
    }
    return t::get($this->message, $__data);
  }
}