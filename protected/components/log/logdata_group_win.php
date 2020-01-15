<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_group_win extends logdata_prototype {
  public $fields = array(
    'link', 'title', 'gold_gain', 'exp_gain'
  );
  public $message ="Ваша команда <a href=\"%s\">выиграла</a> групповой бой «%s», приз: +%s золота, +%s опыта";
  public $type = 'group_win';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $link, $title, $gold_gain, $exp_gain){
    $data = array(
      'link' => $link,
      'title' => $title,
      'gold_gain' => $gold_gain,
      'exp_gain' => $exp_gain,
    );
    self::save($player_id, Log::CATEGORY_PVP , $data);
  }
}