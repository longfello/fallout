<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_supermob_location extends logdata_prototype {
  public $fields = array(
    'buyed', 'region', 'dir_x', 'dir_y'
  );
  public $message ='';
  public $type = 'supermob_location';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $buyed, Region $region, $dir_x, $dir_y){
    $data = array(
      'buyed' => $buyed,
      'region' => (array)$region,
      'dir_x' => $dir_x,
      'dir_y' => $dir_y
    );
    self::save($to_id, Log::CATEGORY_ETC , $data);
  }
  
  public function render($data){
    return $data['buyed']?t::get('Охотники выследили монстра и убили его. Жаль, что ты не успел поучаствовать в этом. Рекомендую теперь поискать в районе, от которого до Water City %s-%s километров на %s и %s-%s километров на %s. Поторопись', array($data['region']['y1'],$data['region']['y2'], $data['dir_y'], $data['region']['x1'],$data['region']['x2'], $data['dir_x'])):t::get("Ты опять опоздал! Теперь тебе снова придется заплатить за информацию. Заходи в бар, как надумаешь. Всегда рад тебя видеть.");
  }
}