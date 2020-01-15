<?php

class AchieveRules
{
    /**
     * Правила для серы
     * @param $count Количество текущего опыта
     * @return Уровень достижения
     */
    public static function sulfur($count)
    {
        if ($count >= 1150 and $count < 2350)
            return 1;
        elseif ($count >= 12950 and $count < 19950)
            return 2;
        elseif ($count >= 44950)
            return 3;
        else
            return false;
    }


    /**
     * Правила для БДСМ на серном руднике
     */
    public static function bawdry()
    {
        return 1;
    }


}

?>