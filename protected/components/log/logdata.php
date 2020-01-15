<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata {
  public static function render($id_or_array){
    if (is_array($id_or_array)) {
      $model_data = $id_or_array;
    } else {
      $model_data = Log::model()->findByPk($id_or_array)->getAttributes();
    }

    $data = $model_data['data'];
    if ($data) {
      $data = CJavaScript::jsonDecode($data);
      if (isset($data['type'])) {
        $class =  'logdata_'.$data['type'];
        try {
          $log = new $class();
          $s = $log->render($data);
        } catch(Exception $e) {
          $s = $model_data['log'];
        }
        $model_data['log'] = $s;
      }
    }

    return $model_data['log'];
  }
}