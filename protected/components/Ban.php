<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 03.05.16
 * Time: 11:38
 */
class Ban {
  const BAN_SITE = 'site';
  const BAN_CHAT = 'chat';
  const BAN_ADVERT = 'advert';
  const BAN_ANY = false;
  
  const TOR_USER = '*** tor ***';

  public static $excludedPages = array('/site/tor', '/banned/location/site', '/banned/location/chat', '/banned/location/advert', '/logout');

  public static function check($location = self::BAN_SITE, $redirect = true){
    if (self::excludedPage()) return false;

    $reasons = self::getReasons($location);
    $banned  = count($reasons) > 0;
    if ($banned && $redirect) {
      self::redirect($location, $reasons);
    }
    return $banned;
  }

  static public function excludedPage(){
    return in_array($_SERVER['REQUEST_URI'], self::$excludedPages);
  }

  public static function redirect($location = self::BAN_SITE, $reasons = array()){
    $isTor = false;
    foreach($reasons as $reason) {
      if ($reason->login == self::TOR_USER) $isTor = true;
    }

    if ($isTor) {
      Yii::app()->request->redirect('/site/tor');
    } else {
      Yii::app()->request->redirect('/banned/location/'.$location);
    }
  }

  /**
   * @param $location
   * @return BannedPlayers[]
   */
  public static function getReasons($location = false){
    $reasons = array();

    if ($player = Yii::app()->stat->model) {
      $reasons = array_merge($reasons, self::checkPlayer($location, $player));
    }

    $reasons = array_merge($reasons, self::checkIp());

    return $reasons;
  }

  public static function checkIp($ip = false){
    $criteria = new CDbCriteria();
    $criteria->addCondition("t.ip = INET_ATON(:ip)");
    $criteria->addCondition("ban.block_ip = 1");
    $criteria->addCondition("`until` > NOW() OR `until` IS NULL");

    $criteria->with = array('ban');
    $criteria->together = true;

    if (!$ip) {
      if (Yii::app()->stat && Yii::app()->stat->model) {
        $ip = Yii::app()->stat->model->ip;
      } else {
        $ip = self::getIP();
      }
    }
    $criteria->params[':ip'] =  $ip?$ip:Yii::app()->stat->model->ip;
    
    $reasons = array();
    foreach(BannedIp::model()->findAll($criteria) as $model){
      if ($model->ban){
        $reasons[] = $model->ban;
      }
    }

    return $reasons;
  }

  public static function checkPlayer($location, Players $player){
    if (!in_array($location, array(
      self::BAN_ADVERT,
      self::BAN_CHAT,
      self::BAN_SITE
    ))){
      $location = false;
    }

    $criteria = new CDbCriteria();
    $criteria->addCondition('player_id = :player_id');
    if ($location) {
      $criteria->addCondition("`{$location}` = 1");
      $criteria->addCondition("`active_{$location}` = 0");
    }
    $criteria->addCondition("(`until` IS NULL OR `until` > NOW())");
    $criteria->params[':player_id'] = $player->id;

    $models =  BannedPlayers::model()->findAll($criteria);
    /** @var $models BannedPlayers[] */
    foreach($models as $model) {
      if ($model->block_ip == 1) {
        self::checkIpPresent($model, $player);
      }
    }

    return $models;
  }

  public static function getBanTime($location, $player = false) {
    if ($player) {
      $bans = array();

      if (is_array($player) && isset($player['id'])) {
        $player = Players::model()->findByPk($player['id']);
      }
      if (is_object($player) && property_exists($player, 'id') && get_class($player) != 'Players') {
        $player = Players::model()->findByPk($player->id);
      }
      if (is_object($player) && get_class($player) == 'Players') {
        $bans = array_merge($bans, self::checkPlayer($location, $player));
        $bans = array_merge($bans, self::checkIp($player->ip));
      }
    } else {
      $bans = self::getReasons($location);
    }
    $time = 0;
    foreach ($bans as $ban){
      $btime = $ban->until?strtotime($ban->until):strtotime('now +100 years');
      if ($time < $btime) { $time = $btime; }
    }
    return $time;
  }

  private static function checkIpPresent(BannedPlayers $model, Players $player){
    $criteria = new CDbCriteria();
    $criteria->addCondition("ban_id = :ban_id");
    $criteria->addCondition("ip = :ip");


    $criteria->params[':ban_id'] = $model->id;
    $criteria->params[':ip'] = $player->ip;

    if (!BannedIp::model()->find($criteria)){
      $ban = new BannedIp();
      $ban->ban_id = $model->id;
      $ban->ip = new CDbExpression('INET_ATON(:ip)',array(':ip' => $player->ip));
      $ban->save();
    }
  }

  public static function getIP(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
    {
      $ip = getenv("HTTP_CLIENT_IP");
    }
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    {
      $ip = getenv("HTTP_X_FORWARDED_FOR");
    }
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
    {
      $ip = getenv("REMOTE_ADDR");
    }
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
    {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
      $ip = 'unknown';
    }
    return $ip;
  }
}