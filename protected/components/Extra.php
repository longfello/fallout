<?php

class Extra
{
    /**
     * Получить иконку ранга пользователя и ссылку на страницу управления
     */
    public static function getUserRankIcon($rank)
    {
        $icon = null;
        switch ($rank)
        {
            case 'Админ':
                $icon = '<a href="/admin"><img style="vertical-align:middle" src="/images/admin.gif" alt="'.t::get('Админ').'"></a>';
                break;
            case 'Модер':
                $icon = '<a href="/admin"><img style="vertical-align:middle" src="/images/moder.gif" alt="'.t::get('Модератор').'"></a>';
                break;
            case 'Тестер':
	            $icon = '<a href="/admin"><img style="vertical-align:middle" src="/images/admin.gif" alt="'.t::get('Тестер').'"></a>';
	            break;
	        default:
		        $icon = '<img style="vertical-align:middle" src="/images/null.gif" width="12">';
		        break;
        }

        return $icon;
    }


    /**
     * Получить иконку клана
     * @param $clanId int Ид клана
     * @return string
     */
    public static function getClanIcon($clanId)
    {
        $imageDir = '/images/clans/';

        if ($clanId)
        {
            return CHtml::image($imageDir . $clanId . '.gif', t::get('Ваш клан'), array('width' => '17px', 'height' => '15px'));
        }

        return '';
    }


    /**
     * Получить имя css класса для ника в чате
     */
    public static function getChatCssColorUser($rank)
    {
        $adminRanks = array('Админ', 'Модер', 'Чат-Модер');
        if (in_array($rank, $adminRanks))
            return 'colorAdmin';
        else
            return 'colorPlayer';
    }


    /**
     * Добавить тэг, если в сообщении есть текущий ник
     */
    public static function forMe($str)
    {
        return str_replace(Yii::app()->stat->user, '<span class="forMe">' . Yii::app()->stat->user . '</span>', $str);
    }


    /**
     * Получить процент текущих статов для статус бара: отношение текущего количества к максимальному
     */
    public static function getBarStatPercent($currentValue, $maxValue)
    {
        if ($currentValue >= $maxValue)
        {
            $percent = 100;
        }
        else
        {
            $percent = round(($currentValue / $maxValue) * 100);
        }

        return $percent;
    }



    /**
     * Получить требуемое количество опыта игроку для получения следующенго уровня
    */
    public static function getUserExperienceBeforeNextLevel()
    {
        if(Yii::app()->stat->level >= 190)
        {
            $expn = ((Yii::app()->stat->level * 10000) + (Yii::app()->stat->level * 30) * (Yii::app()->stat->level * 25));
        }
        elseif(Yii::app()->stat->level >= 170)
        {
            $expn = ((Yii::app()->stat->level * 3000) + (Yii::app()->stat->level * 26) * (Yii::app()->stat->level * 21));
        }
        elseif(Yii::app()->stat->level >= 150)
        {
            $expn = ((Yii::app()->stat->level * 1000) + (Yii::app()->stat->level * 24) * (Yii::app()->stat->level * 18));
        }
        elseif(Yii::app()->stat->level >= 120)
        {
            $expn = ((Yii::app()->stat->level * 300) + (Yii::app()->stat->level * 21) * (Yii::app()->stat->level * 15));
        }
        elseif(Yii::app()->stat->level >= 105)
        {
            $expn = ((Yii::app()->stat->level * 150) + (Yii::app()->stat->level * 18) * (Yii::app()->stat->level * 13));
        }
        elseif(Yii::app()->stat->level >= 100)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level * 11));
        }
        elseif(Yii::app()->stat->level >= 90)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level * 10));
        }
        elseif(Yii::app()->stat->level >= 80)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level * 9));
        }
        elseif(Yii::app()->stat->level >= 70)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level * 8));
        }
        elseif(Yii::app()->stat->level >= 60)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level * 7));
        }
        elseif(Yii::app()->stat->level >= 50)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level * 6));
        }
        elseif(Yii::app()->stat->level >= 40)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level) * 5);
        }
        elseif(Yii::app()->stat->level >= 30)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level) * 4);
        }
        elseif(Yii::app()->stat->level >= 20)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level) * 3);
        }
        elseif(Yii::app()->stat->level >= 10)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level) * 2);
        }
        elseif(Yii::app()->stat->level < 10)
        {
            $expn = ((Yii::app()->stat->level * 50) + (Yii::app()->stat->level * 15) * (Yii::app()->stat->level));
        }

        return $expn;
    }


    /**
     * Получить требуемое количество опыта игроку для получения следующенго уровня в процентах
    */
    public static function getUserExperienceBeforeNextLevelInPercent()
    {
	    $next = Extra::getUserExperienceBeforeNextLevel();
        $pct = $next?((Yii::app()->stat->exp  / $next) * 100):0;
        $pct = round($pct);
        return $pct;
    }


    public static function getPetExperienceBeforeNextLevel(Players $player){
	    $expn_pet = 0;
	    if($player->pet){
		    if($player->pet->level >= 150)
		    {
			    $expn_pet = (($player->pet->level * 1000) + ($player->pet->level * 40) * ($player->pet->level * 30)) / 4;
		    }
		    elseif($player->pet->level >= 120)
		    {
			    $expn_pet = (($player->pet->level * 300) + ($player->pet->level * 30) * ($player->pet->level * 25)) / 4;
		    }
		    elseif($player->pet->level >= 105)
		    {
			    $expn_pet = (($player->pet->level * 150) + ($player->pet->level * 25) * ($player->pet->level * 15)) / 4;
		    }
		    elseif($player->pet->level >= 100)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level * 11)) / 4;
		    }
		    elseif($player->pet->level >= 90)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level * 10)) / 4;
		    }
		    elseif($player->pet->level >= 80)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level * 9)) / 4;
		    }
		    elseif($player->pet->level >= 70)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level * 8)) / 4;
		    }
		    elseif($player->pet->level >= 60)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level * 7)) / 4;
		    }
		    elseif($player->pet->level >= 50)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level * 6)) / 4;
		    }
		    elseif($player->pet->level >= 40)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level) * 5) / 4;
		    }
		    elseif($player->pet->level >= 30)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level) * 4) / 4;
		    }
		    elseif($player->pet->level >= 20)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level) * 3) / 4;
		    }
		    elseif($player->pet->level >= 10)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level) * 2) / 4;
		    }
		    elseif($player->pet->level < 10)
		    {
			    $expn_pet = (($player->pet->level * 50) + ($player->pet->level * 15) * ($player->pet->level)) / 4;
		    }

		    $expn_pet = round($expn_pet);
	    }
	    return $expn_pet;
    }
}