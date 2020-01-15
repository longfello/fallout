<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_winlog_winclan extends combatlog_prototype{
  public $type = __CLASS__;
  public $message ='<div class="winner"><span class="info"></span><span>%s!</span></div><br />%s';
  public $fields = array('winner_id', 'exp', 'clan_exp', 'gold');

  public static function add(){
    $args = func_get_args();
    $model = new self();
    return call_user_func_array(array($model, '__add'), $args);
  }
  
  public function __add($winner_id, $exp, $clan_exp, $gold){
    $data = array(
      'winner_id' => $winner_id,
      'exp'       => $exp,
      'clan_exp'  => $clan_exp,
      'gold'      => $gold,
      'type'      => $this->type
    );
    return $data;
  }

  public function render($data) {
    $winner   = Players::model()->findByPk($data['winner_id']);

    $__data = array(
      'pre_text'   => t::get('Победил боец %s', $winner->getLinkAndName()),
      'post_text'  => t::get('%s получает %s опыта, из которого <span class="battle_win_text">+15%% (%s опыта) за победу над врагом клана</span> и %s <img alt="Золото" src="/images/gold.png"><br>', array($winner->getLinkAndName(), $data['exp'], $data['clan_exp'], $data['gold'])),
    );

    return vsprintf($this->message, $__data);
  }

}