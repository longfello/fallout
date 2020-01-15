<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 27.11.15
 * Time: 10:45
 */
class GameMessages {
  const MSG_NOTICE = 'notice';
  const MSG_ERROR  = 'error';
  const MSG_INFO   = 'info';

  public static function flash($type, $message){
    Yii::app()->user->setFlash($type, $message);
  }

  public static function displayMessages($type = false){
    if ($type) {
      if(Yii::app()->user->hasFlash($type)) {
        $state = ($type == self::MSG_ERROR)?"ui-state-error":"ui-state-highlight";
        ?>
        <div style="padding: 10px" class="highlightUI <?=$state?> ui-corner-all">
          <span style="float: left; margin-right: 5px; padding: 0;" class="ui-icon ui-icon-<?=$type?>"></span>
          <?= Yii::app()->user->getFlash($type); ?>
        </div>
        <?php
      }
    } else {
      self::displayMessages(self::MSG_ERROR);
      self::displayMessages(self::MSG_NOTICE);
      self::displayMessages(self::MSG_INFO);
    }
  }

}