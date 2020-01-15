<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_group_penalty extends logdata_prototype {
  public $fields = array(
    'clan_name', 'title'
  );
  public $message ='Клан "%s" не явился на бой %s, за что получает штрафное очко в рейтинге "<a href="/clans.php?view=clan_wars">Клановые войны</a>".';
  public $type = 'group_penalty';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($players_models, $clan_name, $title){
    $data = array(
      'clan_name' => $clan_name,
      'title' => $title,
    );
    foreach ($players_models as $one) {
      self::save($one->id, Log::CATEGORY_PVP , $data);
    }
  }
}