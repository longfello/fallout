<?php
  class UpgradeCommand extends CConsoleCommand {
    public function actionIndex(){
      echo(PHP_EOL);
      echo('Proccess database migration queries...');
      echo(PHP_EOL);


      $this->exec("SET FOREIGN_KEY_CHECKS=0;", "Turnoff foreign keys check");

// players => players_meta  (exchange_mailing, last_killed, last_killed_by, lisa)
      $this->exec("TRUNCATE TABLE players_meta", "Truncate meta table");
      $this->exec("INSERT INTO players_meta (player_id, `key`, `value`) SELECT id, 'lisa', 1 FROM players WHERE lstate=1;", "copy Lisa field");
      $this->exec("INSERT INTO players_meta (player_id, `key`, `value`) SELECT id, 'exchange_mailing', 1 FROM players WHERE exchange_mailing=1;", "copy exchange_mailing/1 field");
      $this->exec("INSERT INTO players_meta (player_id, `key`, `value`) SELECT id, 'exchange_mailing', 0 FROM players WHERE exchange_mailing =0;", "copy exchange_mailing/0 field");

      $this->exec("ALTER TABLE `players` DROP COLUMN `lastkilled`;", "drop lastkilled field");
      $this->exec("ALTER TABLE `players` DROP COLUMN `lastkilledby`;", "drop lastkilledby field");
      $this->exec("ALTER TABLE `players` DROP COLUMN `lstate`;", "drop lstate field");
      $this->exec("ALTER TABLE `players` DROP COLUMN `exchange_mailing`;", "drop exchange_mailing field");

      $this->exec("SET FOREIGN_KEY_CHECKS=1;", "Turnon foreign keys check");

/*
 *
 * 1. Сделать бэкап фс и мускула цели при необходимости
 * 2. Перенести файловую систему новую
 * 3. Обновить картинки с боевого
 * 4. Перенести БД с боевого
 * 5. Выполнить синхрониацию БД в плане добавления новых таблиц
 * 6. Обновить настройки поделючения к БД, путей фс, домена
 * 7. Выполнить миграцию данных (yiic upgrade)
 * 8. Запустить миграцию банов
 * 9. Выполнить полную синхронизацию БД
 * 10.Перенести данные приведенных ниже таблиц с тестового на целевой
 *    // move pet_exp data
 *    // move player_exp data
 *    // move rev_combat_phrases data
 *    // move rev_language data
 *    // move rev_language_translate data
 *    // move rev_language_translate_home data
 *    // move rev_pw_costs data
 *    // move news data
 *    // move page data
 * 11. Установить настройки веб-серверов nginx, apache
 * 12. Настроить домены на яндексе
 * 13. В protected/config/main.php поменять основной домен
 * 14. Установить задания cron
 *
 *
 * */
    }
    public function actionEquipment(){
      echo(PHP_EOL);
      echo('Proccess equipment images migration...');
      echo(PHP_EOL);

	  $models = Equipment::model()->findAll();
	  foreach($models as $model){
		  $pic = $model->pic;
		  $types = [
		  	''      => Equipment::IMAGE_ORIGINAL,
		  	'f'     => Equipment::IMAGE_FEMALE,
		  	'k'     => Equipment::IMAGE_HAND,
		  	'kf'    => Equipment::IMAGE_HAND_FEMALE,
		  	'small' => Equipment::IMAGE_SMALL,
		  ];
		  foreach($types as $key => $type){
			  $from = basedir.'/images/'.$model->getPicSubdir().'/'.$pic.$key.'.png';
			  $to   = basedir.$model->getImagePath($type);
			  echo("copy file: {$from} ... ");
			  if (file_exists($from)) {
				  copy($from, $to);
				  echo('ok');
			  } else {
				  echo('FAIL');
			  }
			  echo(PHP_EOL);
		  }
	  }
	    echo("\r\n All done!");
    }

    public function actionAppearance(){
	    echo(PHP_EOL);
	    echo('Proccess players appearence migration...');
	    echo(PHP_EOL);

	    $ht = [
	    	1 => 'avatar',
		    2 => 'hairstyle',
		    3 => 'beard'
	    ];

	    // PlayerAppearance::model()->deleteAll();
	    echo('Old records deleted...');
	    echo(PHP_EOL);

	    // $models = Appearance::model()->findAll();
	    _mysql_query("delete FROM appearance WHERE user_id NOT IN (select id from players)");
	    $alist = Yii::app()->db->commandBuilder->createSqlCommand("SELECT id FROM appearance_layout")->queryAll();
	    $i = 0;

	    $ofst = 0;
	    $limit = 50;
	    $cnt = 1;
	    touch('dump.sql');
	    $query = '';

	    $overall = Yii::app()->db->commandBuilder->createSqlCommand("SELECT count(*) FROM appearance")->queryScalar();

	    while ($cnt > 0) {
		    $res = _mysql_query("SELECT user_id, avatar, hairstyle, beard FROM appearance LIMIT $limit OFFSET $ofst");
		    $cnt = _mysql_num_rows($res);

		    while($model = _mysql_fetch_array($res)){
			    $i++;
			    echo("memory usage: ". round(memory_get_usage() / 1024 / 1024 ) ."Mb,  {$i} (".round(100*$i/$overall, 1)."%)  \r");
			    $gender = Yii::app()->db->commandBuilder->createSqlCommand("SELECT gender FROM players WHERE id = {$model['user_id']} ")->queryScalar();
			    foreach ($alist as $one){
				    $sql = "SELECT id FROM player_race_appearance_list WHERE race_id = 1 AND gender = '". (($gender == Players::GENDER_MALE)?'male':'female') ."' AND appearance_layout_id = {$one['id']} AND original_filename = '". $model[$ht[$one['id']]] ."' LIMIT 1";
				    $layout = Yii::app()->db->commandBuilder->createSqlCommand($sql)->queryScalar();
				    if ($layout) {
					    $query .= "INSERT IGNORE INTO player_appearance (player_id, appearance_layout_id, player_race_appearance_id) VALUES ({$model['user_id']}, {$one['id']}, $layout);
";
					    if (strlen($query) > 16384) {
						    file_put_contents('dump.sql', $query, FILE_APPEND);
						    unset($query);
						    $query = '';
					    }
				    }
			    }
		    }
		    $ofst += $limit;
	    }

	    file_put_contents('dump.sql', $query, FILE_APPEND);
    }

    public function exec($query, $description = false){
      $description = $description?$description:mb_substr($query, 0, 100);
      echo($description);
      echo(PHP_EOL);
	    Yii::app()->db->commandBuilder->createSqlCommand($query)->execute();
    }

  }

