<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_bank_transfer extends logdata_prototype {
  public $fields = array(
    'sending_gold_without_tax', 'tax'
  );
  public $message ='Вы перевели на свой счет в банке Water City из банка в Токсических пещерах %s <img src=images/gold.png>. Комиссия составила %s <img src=images/gold.png>.';
  public $type = 'bank_transfer';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $sending_gold_without_tax, $tax){
    $data = array(
      'sending_gold_without_tax' => $sending_gold_without_tax,
      'tax' => $tax,
    );
    self::save($player_id, Log::CATEGORY_MARKET , $data);
  }
}