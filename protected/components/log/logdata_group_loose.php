<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_group_loose extends logdata_prototype {
  public $fields = array(
    'link', 'title'
  );
  public $message ="Ваша команда <a href=\"%s\">проиграла</a> групповой бой «%s»...";
  public $type = 'group_loose';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $link, $title){
    $data = array(
      'link' => $link,
      'title' => $title,
    );
    self::save($player_id, Log::CATEGORY_PVP , $data);
  }
}