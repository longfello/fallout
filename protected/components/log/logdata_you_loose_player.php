<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_you_loose_player extends logdata_prototype {
  public $fields = array(
    'link', 'looser_id', 'looser_name', 'goldgain'
  );
  public $message ="Вы <a href='%s'>проиграли</a> бой персонажу <b><a href=/view.php?view=%s>%s</a></b>. Потеряно <b>%s</b> <img src='images/gold.png' alt='Золото'>";
  public $type = 'you_loose_player';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($player_id, $link, $looser_id, $looser_name, $goldgain){
    $data = array(
      'link' => $link,
      'looser_id' => $looser_id,
      'looser_name' => $looser_name,
      'goldgain' => $goldgain
    );
    self::save($player_id, Log::CATEGORY_PVP , $data);
  }
}