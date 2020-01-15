<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class combatlog_renderer {
  public static function render($data){
    if (is_string($data)) {
      $data = CJavaScript::jsonDecode($data);
    }

    $phrase = '';

    if (isset($data['type'])) {
      $class =  $data['type'];

      try {
        $log = new $class();
        $phrase = $log->render($data);
      } catch(Exception $e) {
        $phrase = $e->getMessage();
      }
    }

    return $phrase;
  }
}