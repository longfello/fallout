<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_new_bonuses extends logdata_prototype {
  public $fields = array(
    'mail_items_id'
  );
  public $message ='За достижение нового уровня вы получили бесплатные бонусы. <a href="mail.php?read=%s">Подробнее =></a>';
  public $type = 'new_bonuses';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $mail_items_id){
    $data = array(
      'mail_items_id' => $mail_items_id,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
}