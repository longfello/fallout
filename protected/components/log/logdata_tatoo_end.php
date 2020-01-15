<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_tatoo_end extends logdata_prototype {
  public $fields = array(
    'tatoo_id'
  );
  public $message ='Татуировка <b>"%s"</b> завершила свое действие. Для того, чтобы свести ее, необходимо перейти к <b><a href="/tatoo.php">татуировщику Тулу</a></b>.';
  public $type = 'tatoo_end';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $tatoo_id){
    $data = array(
      'tatoo_id' => $tatoo_id,
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }

  public function render($data){
    if (isset($data['tatoo_id'])){
      $tatoo_name = t::getDb('name', 'tatoos', 'id', $data['tatoo_id']);
      return t::get($this->message, array($tatoo_name));
    } else return '';
  }
}