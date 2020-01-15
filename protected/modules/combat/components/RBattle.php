<?php

class RBattle
{
    /**
     * Получить сообщение для удара
     * @param $gender Пол
     * @return Сообщение
     */
    public static function getAttackMessage($gender)
    {
        $game_battle_cry_man = array (
            t::get("отвесил пинка"),
            t::get("вмазал по морде"),
            t::get("всадил по самое немогу"),
            t::get("выбил всю дурь из"),
            t::get("ударил"),
            t::get("атаковал"),
            t::get("ударил по копчику"),
            t::get("вломил "),
            t::get("сделал очень больно"),
            t::get("ударил с развороту")
        );

        $game_battle_cry_woman = array (
            t::get("отвесила пинка"),
            t::get("вмазала по морде"),
            t::get("всадила по самое немогу"),
            t::get("выбила всю дурь из"),
            t::get("ударила"),
            t::get("атаковала"),
            t::get("ударила по копчику"),
            t::get("вломила"),
            t::get("сделала очень больно"),
            t::get("ударила с развороту")
        );

        $collection = $gender == Players::GENDER_MALE ? $game_battle_cry_man : $game_battle_cry_woman;

        $number = mt_rand(0, count($game_battle_cry_man) - 1);

        return $collection[$number];
    }


    /**
     * Обычная атак врага
     * @return Сообщение лога
     */
    public static function attack($playerOneId, $playerTwoId)
    {
        /** @var  $p1 Players */
        $p1 = Players::model()->findByPk($playerOneId);
        /** @var  $p2 Players */
        $p2 = Players::model()->findByPk($playerTwoId);


        // базовая сила удара
        $att = round($p1->strength + ($p1->agility / 6));
        // базовая сила защиты
        $def = round($p2->defense + ($p2->agility / 6));
        // критовый шанс
        $crit = $p1->agility / max(1, $p2->agility);
        $crit = max(1, round((100 * $crit) / ($crit + 7)));
        // Вариация повреждения
	    $att2 = $att + round($att * $p1->agility / ($p1->agility + 400));

        // Непосредственно атака
	    // Теперь вдарим
		// Дамадж будем рандомизировать...
		$d = mt_rand( max(1, $att-3) , $att2);
		// Для крита дамаж увеличиваем втрое
        if (mt_rand(0, 100) < $crit) {
            $d = round($d * mt_rand(110, 150) / 100);
        }
        $damage = max(0, $d - $def);
        if ($damage == 0) {
            $damage = mt_rand(1, round(1 / 3));
            if (mt_rand(0, 100) < $crit) {
                $damage = round($damage * mt_rand(150, 300) / 100);
            }
        }

        // Аккумулируем дамадж (только действующий)
        $damage = min($p2->hp, $damage);

        $p2->hp -= $damage;

        // Запишем в базу
        //$player = Players::model()->findByPk($p2->id);
        //$player->hp = $p2->hp;
        $p2->save(false);
        $p2->refresh();


        // Если игрок победил
        if ($p2->hp <= 0)
        {
            $gold = self::getGoldForWin($p2);
            $exp = self::getExpForWin($p2);
            $combatUrl = Yii::app()->urlManager->createUrl('combat/default/index', array('combatid' => Yii::app()->combat->combat_id));

            //$combat = Combat::model()->findByPk(Yii::app()->combat->combat_id);
            Yii::app()->combat->status = 'inactive';
            Yii::app()->combat->win_log = t::get('Победил боец %s!<br />%s получает %s опыта и %s <img alt="Золото" src="/images/gold.png">', array($p1->user, $p1->user, $exp, $gold));

            logdata_you_win_player::add($p1->id, $combatUrl, $p2->id, $p2->user, false, $exp, $gold);
//            RLog::add(t::get('Вы <a href="%s">победили</a> персонажа <a href="/view.php?view=%s">%s</a>. Получено %s опыта и %s золота', array($combatUrl, $p2->id, $p2->user, $exp, $gold)), $p1->id);

            Players::setLastKilled($p1->id, $p2->id, $p2->user, Players::KILLER_PLAYER);
            $p1->wins++;
            $p1->save(false);

            Players::setLastKilledBy($p2->id, $p1->id, $p1->user, Players::KILLER_PLAYER);
            $p2->losses--;
            $p2->save(false);
        }

        $logMessage = self::getLogMessage($p1->user, $p2->user, false, $damage, $p2->hp);


        return $logMessage;
    }


    /**
     * Неудачная атака врага
     * @return Сообщение лога
     */
    public static function failedAttack($playerOneId, $playerTwoId)
    {
        /** @var  $p1 Players */
        $p1 = Players::model()->findByPk($playerOneId);
        /** @var  $p2 Players */
        $p2 = Players::model()->findByPk($playerTwoId);

        $p2->hp--;
        $p2->save(false);

        $logMessage = self::getLogMessage($p1->user, $p2->user, true, 1, $p2->hp);

        return $logMessage;
    }


    /**
     * Получить сообщение для лога раунда
     */
    public static function getLogMessage($playerNameOne, $playerNameTwo, $block, $damage, $leftHp)
    {
        if ($block)
        {
            return t::get("%s ударил %s. %s поставил блок. Урон: 1. (Осталось: %s)", array($playerNameOne, $playerNameTwo, $playerNameTwo, $leftHp));
        }
        else
        {
            return t::get("%s ударил %s. Урон: %s. (Осталось: %s)", array($playerNameOne, $playerNameTwo, $damage, $leftHp));
        }
    }


    /**
     * Получить количество опыта за победы
     */
    public static function getExpForWin($looser)
    {
	    /** @var $looser Players */
	    $expgain = $looser->isExpFreeze()?0:(mt_rand(5,10) * $looser->level);
        return $expgain;
    }


    /**
     * Получить количество золота за победу
     */
    public static function getGoldForWin($looser)
    {
        $goldgain = max(0, round(($looser->gold / 10)));

        return $goldgain;
    }


}