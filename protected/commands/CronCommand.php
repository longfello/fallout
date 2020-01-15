<?php
  class CronCommand extends CConsoleCommand
  {
    public function actionIndex()
    {
      // renders the view file 'protected/views/site/index.php'
      // using the default layout 'protected/views/layouts/main.php'
      // $this->render('index');
      $this->processOldCombats();
      echo(PHP_EOL);
      echo('All done!');
      echo(PHP_EOL);
    }

    public function actionStat(){

        $sql='SELECT SUM(`gold`) AS gold, SUM(`bank`) AS bank, SUM(`platinum`) AS platinum FROM `players`';
        $resourse=Players::model()->findBySql($sql);
        $sql='SELECT SUM(`tokens`) AS tokens FROM `outposts`';
        $water=Outposts::model()->findBySql($sql);
        $sql='SELECT SUM(`gold`) AS gold, SUM(`platinum`) AS platinum FROM `clans`';
        $clans=Players::model()->findBySql($sql);

        $data=new Resources;
        $data->date=date('Y-m-d');
        $data->gold=$resourse->gold;
        $data->platinum=$resourse->platinum;
        $data->water=$water->tokens;
        $data->gold_clans=$clans->gold;
        $data->platinum_clans=$clans->platinum;
        $data->bank=$resourse->bank;
        $data->save();

        $users_login_query = _mysql_query("SELECT count(p.id) as kol FROM players p WHERE DATE(last_login_time) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)");
        $users_login = _mysql_fetch_object($users_login_query);
        _mysql_query("INSERT INTO log_logins SET `date`=DATE_ADD(CURDATE(), INTERVAL -1 DAY),`logins`='".$users_login->kol."'");
    }

    public function actionDaily(){

        $plat_price = rand(1500, 2000);
        _mysql_query("update market set platcost='$plat_price'");

        _mysql_query("update players set
            ops=ops + CASE WHEN  mines > 0 and  ops < 35 THEN 10 ELSE 0 END,
            age=age+1,
            energy=GREATEST(max_energy,energy),
            hp=GREATEST(max_hp,hp),
            pohod=CASE WHEN pleft<=1 THEN 320 ELSE 420 END,
            napad=ewins + CASE WHEN pleft<=1 THEN 6 ELSE 11 END,
            pleft=CASE WHEN pleft>0 THEN pleft-1 ELSE 0 END
            WHERE ((UNIX_TIMESTAMP() - lpv) < 108000)
		");

        _mysql_query("update players set
            energy=GREATEST(max_energy,energy),
            hp=GREATEST(max_hp,hp),
            pohod=CASE WHEN pleft<=1 THEN 320 ELSE 420 END,
            napad=ewins + CASE WHEN pleft<=1 THEN 6 ELSE 11 END
            WHERE ((UNIX_TIMESTAMP() - lpv) > 108000);
		");

        _mysql_query("UPDATE players SET hp = GREATEST(max_hp, hp)");

        _mysql_query("delete from log where unread='T'");

        $res = _mysql_query("SELECT id FROM players WHERE (UNIX_TIMESTAMP() - lpv) < 108000");
        while($row = _mysql_fetch_assoc($res))
        {
            _mysql_query("update outposts set turns=turns+10 where turns < 25 AND owner = {$row['id']}");
        }
        _mysql_query("update clanwars set turns=turns+5 where turns < 25");

        _mysql_query("delete from register");

        _mysql_query("TRUNCATE TABLE battle");

        _mysql_query("UPDATE pets SET hp=max_hp");

        //обновление рейтингов начало
        $i=1;
        $sql = _mysql_query("SELECT clan, SUM(n) AS ch FROM players JOIN st_players st ON players.id=st.player WHERE clan>0 and clan <>13 and a='prW' GROUP BY clan ORDER BY ch DESC LIMIT 5");
        WHILE ($upd = _mysql_fetch_array($sql)) {
            $name = _mysql_query("update  cratings  set clan_profW = '$upd[clan]', countW='$upd[ch]' Where place=$i");
            $i++;
        }
        $i=1;
        $sql = _mysql_query("SELECT clan, SUM(n) AS ch FROM players JOIN st_players st ON players.id=st.player WHERE clan>0 and clan <>13 and a='prD' GROUP BY clan ORDER BY ch DESC LIMIT 5");
        WHILE ($upd = _mysql_fetch_array($sql)) {
            $name = _mysql_query("update cratings  set clan_profD = '$upd[clan]', countD='$upd[ch]' Where place=$i");
            $i++;
        }
        $i=1;
        $sql = _mysql_query("SELECT clan, SUM(n) AS ch FROM players JOIN st_players st ON players.id=st.player WHERE clan>0 and clan <>13 and a='prF' GROUP BY clan ORDER BY ch DESC LIMIT 5");
        WHILE ($upd = _mysql_fetch_array($sql)) {
            $name = _mysql_query("update  cratings  set clan_profF = '$upd[clan]', countF='$upd[ch]' Where place=$i");
            $i++;
        }
        $i=1;
        $sql = _mysql_query("SELECT clan, SUM(st.amount) AS ch FROM players  LEFT JOIN st_npc_kills st ON players.id=st.player WHERE clan>0 and clan <>13 GROUP BY clan ORDER BY ch DESC LIMIT 5");
        WHILE ($upd = _mysql_fetch_array($sql)) {
            $name = _mysql_query("update  cratings  set clan_npc_kills = '$upd[clan]', count_npc_kills='$upd[ch]' Where place=$i");
            $i++;
        }
        //обновление рейтингов конец

        // tatoo start
        _mysql_query("UPDATE players pl SET napad = napad + @a WHERE (SELECT @a:=SUM(t.napad) FROM tatoos t JOIN utatoos u ON u.tatoo = t.id WHERE u.owner = pl.id) > 0");

        $data = _mysql_query("SELECT u.id, u.owner, t.name, t.add_strength, t.add_agility, t.add_defense, t.add_max_energy, t.add_max_hp FROM utatoos u JOIN tatoos t ON u.tatoo = t.id WHERE u.lifetime < ". time() . " AND u.lifetime <> 0 AND u.timeout = 0");
        while ($tatoo = _mysql_fetch_assoc($data)) {
            $tatoo = correct_tatoo($tatoo);
            _mysql_query("UPDATE players SET napad = napad ".($tatoo['add_max_hp'] ? ", `max_hp`=`max_hp`-".$tatoo['add_max_hp'] : "" ).($tatoo['add_max_energy'] ? ", `max_energy`=`max_energy`-".$tatoo['add_max_energy'] : "" ).($tatoo['add_strength'] ? ", `strength`=`strength`-".$tatoo['add_strength'] : "" ).($tatoo['add_agility'] ? ", `agility`=`agility`-".$tatoo['add_agility'] : "" ).($tatoo['add_defense'] ? ", `defense`=`defense`-".$tatoo['add_defense'] : "" )." WHERE `id`={$tatoo['owner']}");
            _mysql_query("UPDATE utatoos SET timeout = 1 WHERE owner={$tatoo['owner']} AND id={$tatoo['id']}");
            logdata_tatoo_end::add($tatoo['owner'], $tatoo['id']);
        }
        // tatoo end

        // exchange start
        _mysql_query("DELETE FROM exchange WHERE UNIX_TIMESTAMP(add_date) + 604800 < UNIX_TIMESTAMP(NOW())");
        // exchange end


        // Возврат товара с рынка в инвентарь через N дней
        $sold_items_res = _mysql_query("
            SELECT
                `id`,
                `seller`,
                `item`,
                `creationdate`
            FROM
                `imarket`
            WHERE TIMESTAMPDIFF(DAY, `set_date`, NOW()) > 30
        ");

        $sold_items_msg = array();
        while ($row = _mysql_fetch_assoc($sold_items_res))
        {
            @$sold_items_msg[$row['seller']][$row['item']]++;

            _mysql_query("INSERT INTO `bstore` (`player`, `item`, `creationdate`) VALUES ('{$row['seller']}', '{$row['item']}', '{$row['creationdate']}')");
            _mysql_query("DELETE FROM `imarket` WHERE `id` = {$row['id']}");
        }


        foreach ($sold_items_msg as $key => $value)
        {   $goods = array();
          $player   = Players::model()->findByPk($key);
          $player_name = $player->user;
          /** @var $player Players */
          t::getInstance()->setLanguage($player->lang_slug);

            foreach ($value as $k => $v)
            {
                $item_name = _mysql_fetch_assoc(_mysql_query("SELECT `name` FROM `equipment` WHERE `id` = {$k}"));

                $item_name['name'] = t::getDb('name', 'equipment', 'id', $k);

                $goods[] = "\"{$item_name['name']}\" х {$v} ".t::get('шт').".";
            }
            $mail_msg = t::encSQL("%s, твой товар не пользуется спросом. В течение 30 дней никто не захотел его купить. В твою кладовку возвращено: %s", array($player_name, implode(', ', $goods)));

	        _mysql_query("INSERT INTO `mail` (`sender`, `senderid`, `owner`, `subject`, `body`) VALUES ('".t::encSQL('Хозяин рынка')."', 0, {$key}, '".t::encSQL('Возврат вещей')."',  '$mail_msg')");
        }

        //Лиза
        _mysql_query("DELETE FROM `players_meta` WHERE `key`='lisa'");

	    // Доллары NKR
	    $event = NkrEvents::getCurrentEvent();
	    if ($event){
		    $query = "SELECT (date_end - INTERVAL 3 DAY) = DATE(NOW()) FROM rev_nkr_events WHERE event_id = {$event->event_id}";
		    $sendMessages = Yii::app()->db->createCommand($query)->queryScalar();
		    if ($sendMessages){
			    foreach(Language::model()->findAll() as $language) {
				    t::getInstance()->setLanguage( $language->slug );
				    $sender  = t::encSQL('Администрация');
				    $subject = t::encSQL('mail_nkr_3day_subject');
				    $body    = t::encSQL('mail_nkr_3day_body');


				    $query = "
INSERT INTO mail(sender, subject, body, unread, kbox, senderid, owner, senderdel, ownerdel, dt)
(
  SELECT '{$sender}' as sender, '{$subject}' as `subject`, '{$body}' as body, 'N' as unread, 'N' as kbox, 9 as senderid, id as `owner`, 'N' as senderdel, 'N' as ownerdel, NOW() as dt
  FROM players WHERE lang_slug = '{$language->slug}'
)";
				    Yii::app()->db->createCommand($query)->execute();
			    }
		    }
	    } else {
		    // Если закончилось событие - обнуляем баксы
		    $query = "DELETE FROM players_meta WHERE `key` IN ('".PlayersMeta::KEY_NKR_DOLLARS_TOTAL."','".PlayersMeta::KEY_NKR_DOLLARS_SPENT."','".PlayersMeta::KEY_NKR_DOLLARS_IDS."')";
		    _mysql_query($query);
	    }
    }

    public function actionHourly(){
        $lab_player_res = _mysql_query("
                SELECT p.id as id,
                TIMESTAMPDIFF(SECOND, `hike_start`, NOW()) as time_staying,
                duration+duration_equipment as tduration
                FROM players p
                INNER JOIN caves_player cp ON cp.user_id=p.id
                WHERE p.travel_place = '/labyrinth.php';");

        while ($row = _mysql_fetch_assoc($lab_player_res))
        {
            if ($row['time_staying']>$row['tduration']) {
                _mysql_query("UPDATE players SET `labyrinth_y` = 0, `labyrinth_x` = 0, travel_place='/caves.php' WHERE id={$row['id']}");
                _mysql_query("UPDATE `caves_player` SET `duration` = 0, duration_equipment = 0 WHERE `user_id` = {$row['id']}");
            }
        }

	    $this->actionAuctions();
	    CronJobs::run();
//	    $this->runMails();
    }

	public function actionMail(){
		$this->runMails();
	}

	public function actionGifts(){
	    $this->runGifts();
    }

    public function processOldCombats(){
      echo('Process old combats: ');
      $criteria = new CDbCriteria();
      $criteria->addCondition("`status` = '".Combat::STATUS_ACTIVE."' AND `time` < NOW() - INTERVAL 1 minute");

      $models = Combat::model()->findAll($criteria);
      foreach($models as $model){
        /** @var $model Combat */
        $round = 1;
        while(($model->status == Combat::STATUS_ACTIVE) && ($round < 20)){
          $model->nextRound();
        }
      }

      echo(count($models));
      echo(" done!".PHP_EOL);
    }

    public function actionAuctions(){
	    $time=time();
	    $stime = 60*60*24*3; //3 дня
	    $bettime = 24*60*60;
	    $auctclosed = _mysql_query("SELECT id,player FROM auctions WHERE close='N' and ($time - start_time > $stime) and ($time - bet_time > $bettime)");
	    WHILE ($upd = _mysql_fetch_array($auctclosed)) {
	    	echo("Process auction #{$upd['id']}\r\n");

		    $player   = Players::model()->findByPk($upd['id']);
		    /** @var $player Players */
		    t::getInstance()->setLanguage($player->lang_slug);

		    _mysql_query("INSERT INTO `players` () VALUES()");
		    $older_user_new_id = _mysql_insert_id(); // Новый id для старого игрока
		    _mysql_query("DELETE FROM `players` WHERE `id` = {$older_user_new_id}");

		    $this->perenos($older_user_new_id, $upd['id']);
		    $this->perenos($upd['id'], $upd['player']);
		    $subject= t::get("Аукционы ID");
		    $body = t::get('Вы выиграли аукцион! Ваш новый ID - %s', array($upd['id']));
		    _mysql_exec("insert into mail (sender,senderid,owner,subject,body) values ('<b>".t::encSQL('Аукцион')."</b>','0','$upd[id]','$subject','$body')");
	    }
    }
	private function perenos ($new_id, $old_id)
	  {
		  $this->query("UPDATE `bstore` SET `player` = '$new_id' WHERE `bstore`.`player` =$old_id;");
		  $this->query("UPDATE `chat` SET `user` = '$new_id' WHERE `chat`.`user` =$old_id;");
		  $this->query("UPDATE `chat_clans` SET `user` = '$new_id' WHERE `user` =$old_id;");
		  $this->query("UPDATE `imarket` SET `seller` = '$new_id' WHERE `imarket`.`seller` =$old_id;");
		  $this->query("UPDATE `rev_bank_history` SET `from_player` = '$new_id' WHERE `rev_bank_history`.`from_player` =$old_id;");
		  $this->query("UPDATE `rev_bank_history` SET `to_player` = '$new_id' WHERE `rev_bank_history`.`to_player` =$old_id;");
		  $this->query("UPDATE `log` SET `owner` = '$new_id' WHERE `log`.`owner` =$old_id;");
		  $this->query("UPDATE `mail` SET `owner` = '$new_id' WHERE `mail`.`owner` =$old_id;");
		  $this->query("UPDATE `mail` SET `senderid` = '$new_id' WHERE `mail`.`senderid` =$old_id;");
		  $this->query("UPDATE `outposts` SET `owner` = '$new_id' WHERE `outposts`.`owner` =$old_id;");
		  $this->query("UPDATE `pets` SET `owner` = '$new_id' WHERE `pets`.`owner` =$old_id;");
		  $this->query("UPDATE `players` SET `id` = '$new_id' WHERE `players`.`id` =$old_id;");
		  $this->query("UPDATE `players_social` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `pmarket` SET `seller` = '$new_id' WHERE `pmarket`.`seller` =$old_id;");
		  $this->query("UPDATE `pmarket_history` SET `seller` = '$new_id' WHERE `pmarket_history`.`seller` =$old_id;");
		  $this->query("UPDATE `pmarket_history` SET `buyer` = '$new_id' WHERE `pmarket_history`.`buyer` =$old_id;");
		  $this->query("UPDATE `questblocks` SET `owner` = '$new_id' WHERE `questblocks`.`owner` =$old_id;");
		  $this->query("UPDATE `st_items` SET `player` = '$new_id' WHERE `st_items`.`player` =$old_id;");
		  $this->query("UPDATE `st_money_found` SET `player` = '$new_id' WHERE `st_money_found`.`player` =$old_id;");
		  $this->query("UPDATE `st_npc_kills` SET `player` = '$new_id' WHERE `st_npc_kills`.`player` =$old_id;");
		  $this->query("UPDATE `st_players` SET `player` = '$new_id' WHERE `st_players`.`player` =$old_id;");
		  $this->query("UPDATE `uequipment` SET `owner` = '$new_id' WHERE `uequipment`.`owner` =$old_id;");
		  $this->query("UPDATE `upresents` SET `owner` = '$new_id' WHERE `upresents`.`owner` =$old_id;");
		  $this->query("UPDATE `upresents` SET `giver` = '$new_id' WHERE `upresents`.`giver` =$old_id;");
		  $this->query("UPDATE `user_recipes` SET `user_id` = '$new_id' WHERE `user_recipes`.`user_id` =$old_id;");
		  $this->query("UPDATE `eq_temp_effect` SET `player_id` = '$new_id' WHERE `eq_temp_effect`.`player_id` =$old_id;");
		  $this->query("UPDATE `clans` SET `owner` = '$new_id' WHERE `clans`.`owner` =$old_id;");
		  $this->query("UPDATE `clans` SET `coowner` = '$new_id' WHERE `clans`.`coowner` =$old_id;");
		  $this->query("UPDATE `appearance` SET `user_id` = '$new_id' WHERE `appearance`.`user_id` =$old_id;");
		  $this->query("UPDATE `privacy` SET `player_id` = '$new_id' WHERE `privacy`.`player_id` =$old_id;");
		  $this->query("UPDATE `auctions` SET `close` = 'Y' WHERE `auctions`.`id` =$new_id;");
		  $this->query("UPDATE `utatoos` SET `owner` = '$new_id' WHERE `utatoos`.`owner` =$old_id;");
		  $this->query("UPDATE `cstore_h` SET `from_player` = '$new_id' WHERE `cstore_h`.`from_player` =$old_id;");
		  $this->query("UPDATE `equipment` SET `owner` = '$new_id' WHERE `equipment`.`owner` =$old_id;");
		  $this->query("UPDATE `tatoos` SET `owner` = '$new_id' WHERE `tatoos`.`owner` =$old_id;");
		  $this->query("UPDATE `newuser_completed` SET `user_id` = '$new_id' WHERE `user_id` =$old_id;");
		  $this->query("UPDATE `rev_achieve_setted` SET `user_id` = '$new_id' WHERE `user_id` =$old_id;");
		  $this->query("UPDATE `rev_experience_worker` SET `user_id` = '$new_id' WHERE `user_id` =$old_id;");

		  //$this->query("UPDATE `toxloan_history` SET `from_player` = '$new_id' WHERE `from_player` =$old_id;");
		  //$this->query("UPDATE `toxloan_history` SET `to_player` = '$new_id' WHERE `to_player` =$old_id;");
		  $this->query("UPDATE `toxstore` SET `player` = '$new_id' WHERE `player` =$old_id;");
		  $this->query("UPDATE `toxbank` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `toximarket` SET `seller` = '$new_id' WHERE `seller` =$old_id;");
		  $this->query("UPDATE `rev_chat_clan` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `rev_chat_clan` SET `to_player` = '$new_id' WHERE `to_player` =$old_id;");
		  $this->query("UPDATE `rev_chat_cave` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `rev_chat_cave` SET `to_player` = '$new_id' WHERE `to_player` =$old_id;");
		  $this->query("UPDATE `rev_chat` SET `to_player` = '$new_id' WHERE `to_player` =$old_id;");
		  $this->query("UPDATE `rev_chat` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `rev_chat_avatar` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");

		  $this->query("UPDATE `players` SET `ref` = '0' WHERE `ref` =$new_id;");
		  $this->query("UPDATE `players` SET `ref` = '$new_id' WHERE `ref` =$old_id;");
		  $this->query("UPDATE `rev_combat_log` SET `data` = REPLACE(`data`, '\"$old_id\"', '\"$new_id\"') WHERE battle_id IN (SELECT combat_id FROM rev_combat WHERE player_1 = $old_id OR player_2 = $old_id);");
		  $this->query("UPDATE `rev_combat_round` SET `player_id` = '$new_id' WHERE battle_id IN (SELECT combat_id FROM rev_combat WHERE  player_1 = $old_id OR player_2 = $old_id) AND `player_id` = $old_id;");
		  $this->query("UPDATE `rev_combat` SET `player_1` = '$new_id' WHERE `player_1` =$old_id;");
		  $this->query("UPDATE `rev_combat` SET `player_2` = '$new_id' WHERE `player_2` =$old_id;");
		  $this->query("UPDATE `rev_combat` SET `win_log` = REPLACE(`win_log`, '\"$old_id\"', '\"$new_id\"') WHERE '$old_id' IN (player_1, player_2);");

		  $this->query("UPDATE `banned` SET `user_id` = '$new_id' WHERE `user_id` =$old_id;");
		  $this->query("UPDATE `banned_players` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");

		  $this->query("UPDATE `player_implant` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `player_meta` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `player_social` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  $this->query("UPDATE `player_appearance` SET `player_id` = '$new_id' WHERE `player_id` =$old_id;");
		  Players::SendCMD($old_id, 'logoutPlayer');
	  }
	private function query($query, $text = ''){
		$text  = $text?$text:$query;
		$begin = microtime(true);

		_mysql_query($query);
		$cnt = _mysql_affected_rows();

		$time_end = microtime(true);
		$time = round($time_end - $begin, 1);

		echo("{$cnt} records, {$time} seconds => {$text} \r\n");
	}


	private function runMails(){
		require_once(basedir.'/inc/class.phpmailer.php');

		$mail                = new PHPMailer();
		$mail->CharSet       = 'utf-8';

		$mailLimit = 20; // отправлять не более N писем за раз...
		$limit = 50; // ограничение на время выполнения, секунд
		$start = $current = microtime(true);

		$criteria = new CDbCriteria();
		$criteria->limit = 1;

		$sended = 0;
		while(($model = Email::model()->find($criteria)) && (($current - $limit) < $start) && ($sended <= $mailLimit)){
			$sended++;
			$current = microtime(true);
			$mail->ClearAddresses();
			$mail->AddAddress($model->to_email, $model->to_name);
			$mail->SetFrom($model->from_email, $model->from_name);
			$mail->AddReplyTo($model->from_email, $model->from_name);
			$mail->MsgHTML($model->body);
			$mail->Subject = $model->subject;
			$mail->Send();
			$model->deleteAllByAttributes([
				'to_email' => $model->to_email,
				'to_name' => $model->to_name,
				'from_email' => $model->from_email,
				'from_name' => $model->from_name,
				'subject' => $model->subject,
				'dt' => $model->dt,
			]);
			echo("Send mail '{$model->subject}' to player {$model->to_name}".PHP_EOL);

		}
	}

	private function runGifts(){
        $limit = 55; // ограничение на время выполнения, секунд
        $start = $current = microtime(true);
        $pcount = 0;

        $criteria = new CDbCriteria();
        $criteria->limit = 1;

        while(($model = Gifts::model()->find($criteria)) && (($current - $limit) < $start)){
            $current = microtime(true);
            $pcount++;

            $playerId = $model->player_id;
            $playerModel = Players::model()->findByPk($playerId);

            if ($playerModel) {
                $items = unserialize($model->items);

                $col_items = 0;
                foreach ($items as $itemId=>$count) {
                    for ($i = 0; $i < $count; $i++) {
                        $col_items++;
                        $storeModel = new Bstore();
                        $storeModel->player = $playerId;
                        $storeModel->item = $itemId;
                        $storeModel->save();
                    }
                }


                $playerModel->napad += $model->napad;
                $playerModel->pohod += $model->pohod;
                $playerModel->pleft += $model->pleft;
                $playerModel->save(false);

                logdata_admin_send_log::add($playerId, $model->text, $model->text_en, '', '');

                echo("Sended gifts to player {$playerId}".PHP_EOL);
            } else {
                echo("{$playerId} doesn't exist".PHP_EOL);
            }

            $model->deleteAllByAttributes([
                'player_id' => $model->player_id,
                'items' => $model->items,
                'napad' => $model->napad,
                'pohod' => $model->pohod,
                'pleft' => $model->pleft,
                'text' => $model->text,
                'text_en' => $model->text_en,
                'dt' => $model->dt,
            ]);
        }
    }
  }