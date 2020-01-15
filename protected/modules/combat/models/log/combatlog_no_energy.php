<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_no_energy extends combatlog_prototype{
  public $type = __CLASS__; //'combatlog_no_energy';
  public $message ='%s не в силах больше сражаться и был жестоко наказан за это %s';
  public $fields = array('looser_id', 'winner_id');

  public static function add(){
    $args = func_get_args();
    $model = new self();
    return call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($battleID, $roundID, $data){
    self::save($battleID, $roundID, $data);
  }

  public function render($data) {
    $winner = Players::model()->findByPk($data['winner_id']);
    $looser = Players::model()->findByPk($data['looser_id']);

    if ($winner && $looser) {
      $__data = array();
      $__data['looser_id'] = $looser->getLinkAndName();
      $__data['winner_id'] = $winner->getLinkAndName();
    } else {
      $__data = array();
      $__data['looser_id'] = '?';
      $__data['winner_id'] = '?';
    }
    return parent::render($__data);
  }
}