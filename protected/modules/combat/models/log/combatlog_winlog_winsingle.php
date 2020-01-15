<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_winlog_winsingle extends combatlog_prototype{
  public $type = __CLASS__;

  public $message ='<div class="winner"><span class="info"></span><span>%s!</span></div><br />%s';
  public $fields = array('winner_id', 'exp', 'gold');

  public static function add(){
    $args = func_get_args();
    $model = new self();
    return call_user_func_array(array($model, '__add'), $args);
  }

  public function __add(combatlog_phrase $phrase, $winner_id, $exp, $gold){
    $data = array(
      'phrase'    => $phrase->getData(),
      'winner_id' => $winner_id,
      'exp'       => $exp,
      'gold'      => $gold,
      'type'      => $this->type
    );
    return $data;
  }

  public function render($data) {
    $phrase = combatlog_phrase::load($data['phrase']);
    $winner   = Players::model()->findByPk($data['winner_id']);

    $__data = array(
      'pre_text'   => $phrase->render(),
      'post_text'  => t::get('%s получает %s опыта и %s <img alt="Золото" src="/images/gold.png"><br>', array($winner->getLinkAndName(), $data['exp'], $data['gold'])),
    );

    return vsprintf($this->message, $__data);
  }

}