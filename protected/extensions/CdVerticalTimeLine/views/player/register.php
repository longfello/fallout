<p>Новый игрок #<a href="/admin/players/update?id=<?=$data->id?>"><?=$data->id?></a> зарегистрирован</p>
<?php
  if ($data->provider) {
    $link = TimelineEvent::getProviderLink($data->provider, $data->provider_uid);
    echo("<p>Регистрация осуществлена через социальную сеть {$data->provider}. {$link}</p>");
  }

  $networks = PlayersSocial::model()->findAllByAttributes(array('player_id' => $data->id));
  $links = array();
  foreach($networks as $one){
	  $links [] = CHtml::link($one->provider, $one->profileURL);
  }
  echo(implode(', ', $links));
?>