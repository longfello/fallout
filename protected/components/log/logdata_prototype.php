<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 11:47
 */
class logdata_prototype {
  public $type = 'unknown';
  public $message ='';
  public $fields = array();
/*
  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }
*/
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

  public function save($playerID, $categoryID, $data){
    $data['type'] = $this->type;
    $message = $this->render($data);

    $model = new Log();
//    $model->dt     = date('r');
    $model->owner  = $playerID;
    $model->log    = $message?$message:'-';
    $model->unread = 'F';
    $model->CategoryId = $categoryID;
    $model->data   = CJavaScript::jsonEncode($data);
    if (!$model->save()) {
      var_dump($model->getErrors());
      die();
    }
  }
}