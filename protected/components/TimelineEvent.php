<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 29.03.16
 * Time: 18:21
 */
class TimelineEvent
{
  public static function playerRegister(Players $model){
    Timeline::add('player', 'register', $model->attributes, 'Регистрация нового игрока', '<i class="fa fa-user-plus bg-blue"></i>', '<a class="btn btn-primary btn-xs" href="/admin/players/update?id='.$model->id.'">Редактировать</a>');
  }

  public static function playerChanged(Players $model, $oldData){
    $data = $model->getBasicData();
    $diff = array_diff_assoc($oldData, $data);
    $log  = array();
    foreach($diff as $key => $one){
      if ($oldData[$key] || $data[$key]) {
        $log[$key] = array(
          'old' => $oldData[$key],
          'new' => $data[$key]
        );
      }
    }
    if ($log) {
      $data = array(
        'admin' => Yii::app()->getUser()->id,
        'log'   => $log,
        'id'    => $model->id,
        'name'  => $model->user
      );
      Timeline::add('player', 'change', $data, 'Изменена информация о игроке', '<i class="fa fa-edit bg-blue"></i>', '<a class="btn btn-primary btn-xs" href="/admin/players/update?id='.$model->id.'">Редактировать</a>');
    }
  }

  public static function getProviderLink($provider, $uid){
    $responce = '';
    switch ($provider) {
      case 'vkontakte':
        $responce = "<a href='http://vk.com/id{$uid}' target='_blank'>Страница пользователя</a>";
        break;
      case 'facebook':
        $responce = "<a href='https://www.facebook.com/app_scoped_user_id/{$uid}/' target='_blank'>Страница пользователя</a>";
        break;
      case 'google':
        $responce = "<a href='https://plus.google.com/{$uid}' target='_blank'>Страница пользователя</a>";
        break;
      default:
        $responce = "Идентификатор пользователя в социальной сети: {$uid}";
    }
    return $responce;
  }

}