<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_you_win_player extends logdata_prototype {
  public $fields = array(
    'link', 'looser_id', 'looser_name', 'isNewLevel', 'expgain', 'goldgain'
  );
  public $message ="Вы <a href='%s'>победили</a> персонажа <b><a href=/view.php?view=%s>%s</a></b> %s. Получено <b>%s</b> опыта и <b>%s</b> <img src='/images/gold.png' alt='Золото'>";
  public $type = 'you_win_player';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $link, $looser_id, $looser_name, $isNewLevel, $expgain, $goldgain){
    $isNewLevel = $isNewLevel?t::get("и поднялся на новый уровень"):"";
    $data = array(
      'link' => $link,
      'looser_id' => $looser_id,
      'looser_name' => $looser_name,
      'isNewLevel' => $isNewLevel,
      'expgain' => $expgain,
      'goldgain' => $goldgain
    );
    self::save($player_id, Log::CATEGORY_PVP , $data);
  }
}