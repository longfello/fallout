<?php

class TorCommand extends CConsoleCommand
{
  public $publicIP = '46.165.235.4';

  public function actionIndex(){

    $ips = $this->curl_get("https://check.torproject.org/cgi-bin/TorBulkExitList.py?ip={$this->publicIP}&port=80");
    $ips = explode("\n",$ips);

    $count = 0;

    if ($ips && is_array($ips) && count($ips) > 0) {
      BannedPlayers::model()->deleteAllByAttributes(array('login' => Ban::TOR_USER, 'player_id' => null));
//      Banned::model()->deleteAllByAttributes(array('tor' => 1));
      $count = 0;

      $model = new BannedPlayers();
      $model->login     = Ban::TOR_USER;
      $model->player_id = null;
      $model->site      = 1;
      $model->chat      = 1;
      $model->advert    = 1;
      $model->block_ip  = 1;
      $model->until     = null;
      $model->comment   = 'Tor disabled';
      if (!$model->save()) {
        var_dump($model->getErrors());
        die();
      }

      foreach($ips as $ip){
        if ($this->is_ip($ip)) {
          $ipModel = new BannedIp();
          $ipModel->ban_id = $model->id;
          $ipModel->ip     = ip2long($ip);
          $ipModel->save();
          $count++;
        }
      }
    }

    echo(PHP_EOL);
    echo("$count IP addresses added. All done!");
    echo(PHP_EOL);
  }

  public function curl_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208');
    $ss = curl_exec($ch);
    curl_close($ch);
    return $ss;
  }

  public function is_ip($str) {
    $ret = filter_var($str, FILTER_VALIDATE_IP);

    return $ret;
  }
}