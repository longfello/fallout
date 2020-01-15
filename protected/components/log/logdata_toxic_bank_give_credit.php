<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_toxic_bank_give_credit extends logdata_prototype {
  public $fields = array(
    'link', 'username', 'amount'
  );
  public $message ='<b><a href="%s">%s</a></b> выдал вам кредит. <b>%s</b> золота добавлено на ваш счёт в банке в Токсических пещерах.';
  public $type = 'toxic_bank_give_credit';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $link, $username, $amount){
    $data = array(
      'link'     => $link,
      'username' => $username,
      'amount'   => $amount,
    );
    self::save($player_id, Log::CATEGORY_MARKET , $data);
  }
}