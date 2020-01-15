<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_kick extends combatlog_prototype{
  public $type = __CLASS__;
  public $message ='%s %s %s. Урон: %s. (Осталось: %s)';
  public $fields = array('attacker_id', 'phrase_id', 'defender_id', 'damage', 'hp');

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
    $phrase   = CombatPhrases::model()->findByPk($data['phrase_id']);

    $__data = array(
      'attacker_id'    => '',
      'phrase_id'      => '',
      'defender_id'    => '',
      'damage'         => $data['damage'],
      'hp'             => $data['hp']
    );
    if ($attacker && $defender && $phrase) {
      $__data['attacker_id'] = $attacker->getLinkAndName();
      $__data['defender_id'] = $defender->getLinkAndName();
      $__data['phrase_id']   = $phrase->t('phrase');
    }
    return t::get($this->message, $__data);
  }
}