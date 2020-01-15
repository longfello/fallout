<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class combatlog_prototype {
  public $type = 'unknown';
  public $message ='';
  public $fields = array();

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    return call_user_func_array(array($model, '__add'), $args);
  }

  public function render($data){
    $_data = array();
    foreach ($this->fields as $field){
      if (!isset($data[$field])) {
        trigger_error('Field data not founded: '.$field);
      }
      $_data[$field] = $data[$field];
    }
    
    return t::get($this->message, $_data);
  }

  public function save($battleID, $round, $data){
    $data['type'] = $this->type;

    $model = new CombatLog();
    $model->battle_id  = $battleID;
    $model->round_id   = $round;
    $model->event      = get_called_class();
    $model->data   = CJavaScript::jsonEncode($data);
    if (!$model->save()) {
      var_dump($model->getErrors());
      die();
    }
  }
}