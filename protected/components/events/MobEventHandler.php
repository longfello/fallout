<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 27.10.16
 * Time: 11:39
 */
class MobEventHandler {
	/**
	 * @param $event
	 */
	public static function afterMobKill($event){
		$_enemies    = $event->params['enemies'];
		$playerId    = $event->params['player_id'];
		$data        = isset($event->params['data'])?$event->params['data']:[];

		$player = Players::model()->findByPk($playerId);
		$enemies = [];
		foreach ($_enemies as $one){
			$enemies[] = Npc::model()->findByPk($one->id);
		}
		if ($player && $enemies){
			//***  вот эдеся код при убийстве мобов!
			self::__processNormal($player, $enemies, $data);
			self::__processNKR($player, $enemies, $data);
		}
	}

	/**
	 * @param Players $player
	 * @param Npc[] $enemies
	 */
	private static function __processNormal(Players $player, $enemies, $data) {
		$current_position = new Point( $player->x, $player->y );
		$distance         = map::GetDistanceToWaterCity( $current_position );

		print "<tr><td align=\"center\">" . t::get( 'Персонаж <b>%s</b> победил в этом бою!', $player->user ) . "<br>";
		$loot_sql   = array();
		$loot_names = array();

		foreach ( $enemies as $one ) {
			// Лут
			$one->id = intval( $one->id );
			$asel    = _mysql_exec( "select e.name, e.id, e.type, d.chance, d.amin, d.amax, d.toprand, d.notify  from npcdrop d join equipment e on e.id=d.item where d.npc=$one->id" );
			while ( $drop = _mysql_fetch_object( $asel ) ) {
				// Выдавать или нет
				if ( $drop->chance > mt_rand( 0, $drop->toprand ) ) {
					$drop->name = t::getDb( 'name', 'equipment', 'id', $drop->id );
					$drop->opis = t::getDb( 'opis', 'equipment', 'id', $drop->id );
					// Скока лута дать
					$n = mt_rand( $drop->amin, $drop->amax );
					if ( $n > 0 ) {
						game_st_items( 'DRP', $drop->id, $n );
					}
					for ( $i = 0; $i < $n; $i ++ ) {
						$drop->id   = intval( $drop->id );
						$loot_sql[] = "({$player->id},{$drop->id})";
						if ( $drop->notify == 'Y' || $drop->type == 'W' || $drop->type == 'A' ) {
							$loot_names[] = "<b>$drop->name</b>";
						}
						print t::get( "Персонаж <b>%s</b> получил <b>%s</b>.<br>", array(
							$player->user,
							$drop->name
						) );
					}
				}
			}

			// Занесём в счётчик убиенных уродов
			_mysql_exec( "INSERT INTO st_npc_kills(dt,player,npc,amount) VALUES(CURRENT_DATE,{$player->id},{$one->id},1) ON DUPLICATE KEY UPDATE amount=amount+1" );
			// Добавляем ачивки
			// Ачивка потрошителя
			if ( $achieve_id = RAchieveRules::ripper() ) {
				RAchieve::set( $achieve_id );
			}
			// Ачивка грозы рейдеров
			if ( $achieve_id = RAchieveRules::raiders() ) {
				RAchieve::set( $achieve_id );
			}
			// Ачивка для пастуха геконов
			if ( $achieve_id = RAchieveRules::geckon() ) {
				RAchieve::set( $achieve_id );
			}
			// Ачивка: "Истребитель насекомых"
			if ( $achieve_id = RAchieveRules::destroyer() ) {
				RAchieve::set( $achieve_id );
			}
			// Ачивка: "Убийца нищебродов"
			if ( $achieve_id = RAchieveRules::killer_beggars() ) {
				RAchieve::set( $achieve_id );
			}
			// Ачивка: "Мирмеколог"
			if ( $achieve_id = RAchieveRules::myrmecology() ) {
				RAchieve::set( $achieve_id );
			}
		}

		// Выдадим!
		if ( count( $loot_sql ) > 0 ) {
			$values = implode( $loot_sql, "," );
			_mysql_exec( "insert into uequipment(`owner`, `item`) VALUES {$values}" );
			if ( count( $loot_names ) > 0 ) {
				game_write_chat_me( t::get( "получил" ), t::get( "получила" ), " " . implode( $loot_names, ", " ) . "... " . t::get( '(до города: %s)', $distance ) );
			}
		}

		$expgain      = 0;
		$goldgain     = 0;
		$monsters_ids = array();

		foreach ( $enemies as $one ) {
			$monsters_ids[] = $one->id;
			$expgain += mt_rand( 2 * $one->level, 5 * $one->level );
			$goldgain += round( ( $one->gold ) * mt_rand( 80, 150 ) / 100 );
		}

		$goldgain = isset($data['goldgain'])?$data['goldgain']:$goldgain;

		if ($player->isExpFreeze()) $expgain = 0;

		print t::get( "Вы, <b>%s</b>, получили <b>%s</b> опыта и <b>%s</b> <img src='/images/gold.png'> золота.</td></tr>", array(
			$player->user,
			$expgain,
			$goldgain
		) );

		if ( $goldgain > 0 ) {
			$goldgain = intval( $goldgain );
			_mysql_exec( "INSERT INTO st_money_found(dt,player,money,loot,amount, droploc) VALUES(CURRENT_DATE,{$player->id},'G','N',$goldgain, 1) ON DUPLICATE KEY UPDATE amount=amount+$goldgain" );
		}

		$expgain  = intval( $expgain );
		$goldgain = intval( $goldgain );

		logdata_you_win_monster::add( $player->id, $enemies, $expgain, $goldgain );
		$texp_pet    = 0;
		$expgain_pet = 0;
		if ( $player->pet ) {
			$expgain     = round( $expgain * 0.8 );
			$expgain_pet = round( $expgain / 4 );
			$texp_pet    = intval( $player->pet->exp ) + $expgain_pet;
		}
		/* END MAXV */

		$texp       = ( $player->exp + $expgain );
		$expn = Extra::getUserExperienceBeforeNextLevel();
		if ( $texp >= $expn ) {
			print "<tr><td><center>";
			echo Window::highlight( t::get( 'Поздравляем!<br><b>Вы перешли на новый уровень!<br>И получили +3 свободных стата! <a href="/ap.php" target="_blank">Распределить статы >>></a>' ) ) . "<br />";
			print "</center></td></tr>";
			$player->ap += 3;
			$player->level ++;
			$player->exp = $texp - $expn;
			logdata_you_new_level_monster::add( $player->id, $monsters_ids );
		} else {
			$player->exp += $expgain;
		}

		Players::setLastKilled( $player->id, $enemies[0]->id, $enemies[0]->name, Players::KILLER_MONSTER );

		$perk3  = ( $player->level > 0 ) ? 1 : 0;
		$perkk3 = ( 2 - $perk3 );

		$player->gold += $goldgain;
		$player->energy -= $perkk3;
		$player->save( false );


		if ( $player->pet ) {
			$expgain_pet = intval( $expgain_pet );
			print "<tr><td align=\"center\">" . t::get( 'Ваш питомец получил +%s опыта!', $expgain_pet ) . "</td></tr>";
			$player->pet->exp += $expgain_pet;

			$expn_pet = Extra::getPetExperienceBeforeNextLevel( $player );
			if ( $player->pet && $texp_pet >= $expn_pet ) {
				$apgain_pet = $player->pet->petType?$player->pet->petType->ap:3;
				print "<tr><td align='center' style='color:lightblue;'>" . t::get( 'Поздравляем!<br>Вашему питомцу, <b>%s</b>, удалось перейти на новый уровень!<br>+%s свободных стата!', array($player->user,$apgain_pet)) . "</td></tr>";
				$player->pet->ap += $apgain_pet;
				$player->pet->level++;
				$player->pet->exp = 0;
				logdata_pet_new_level_monster::add( $player->id, $monsters_ids );
			}
			$player->pet->save( false );
		}
    }
	/**
	 * @param Players $player
	 * @param Npc[] $enemies
	 */
	private static function __processNKR(Players $player, $enemies, $data){
		if ($event = NkrEvents::getCurrentEvent()){
			$query = "SELECT COALESCE(sum(amount), 0) sum FROM st_npc_kills WHERE player = {$player->id} AND dt BETWEEN '{$event->date_begin}' AND '{$event->date_end}'";
			$mobCount = Yii::app()->db->createCommand($query)->queryScalar();

			$ids = $player->getMeta(PlayersMeta::KEY_NKR_DOLLARS_IDS, false);
			$_ids = $ids?explode(',', $ids):[0];
			$ids = implode(',', $_ids);
/*
			$query = "SELECT COALESCE(sum(mobs), 0) mobCnt FROM rev_nkr_prices WHERE id IN ({$ids})";
			$mobPayed = Yii::app()->db->createCommand($query)->queryScalar();
*/
			$query = "
SELECT * FROM rev_nkr_prices 
WHERE event_id = {$event->event_id}
AND mobs <= ".$mobCount." AND id NOT IN ($ids) 
ORDER BY mobs DESC
LIMIT 1";

			$oldDollars = $player->getMeta(PlayersMeta::KEY_NKR_DOLLARS_TOTAL, 0);
			$row = Yii::app()->db->createCommand($query)->queryRow();
			if ($row) {
				$dollars = $row['sum'];
				$dollarsTotal = min($oldDollars + $dollars, $event->getMaxDollarsCount());
				$_ids[] = $row['id'];
				$ids = implode(',', $_ids);
				$player->setMeta(PlayersMeta::KEY_NKR_DOLLARS_TOTAL, $dollarsTotal);
				$player->setMeta(PlayersMeta::KEY_NKR_DOLLARS_IDS, $ids);
				logdata_bonus_nkr::add($player->id, $row['mobs'], $dollars);
			}
		}
    }
}