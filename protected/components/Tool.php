<?php

class Tool
{
    /**
     * Возвращает максимально носимый игроком вес
     */
    public static function gameGetMaxCarryWeight()
    {
        //echo '<pre>' . print_r(Yii::app()->stat, 1) . '</pre>';
        $petw = 0;
        if(Yii::app()->pet->name)
        {
            $petw = Yii::app()->pet->add_weight;
        }

        /*
        $criteria = new CDbCriteria();
        $criteria->condition = 'item = :item AND status = :status AND owner = :owner';
        $criteria->params = array(':item' => 115, ':status' => 'E', ':owner' => Yii::app()->stat->id);
        $antigrav =  Uequipment::model()->count($criteria);
        */
	    $antigrav = 0;

        $maxWeight = 500 + (Yii::app()->stat->strength + Yii::app()->eq->add_a_strength * 6) * 15 + 500 * $antigrav + $petw;

        return $maxWeight / 10;
    }

    /**
     * Вывод диалогового блока с описанием
     */
    public static function talkingHead($head, $dialog1, $dialog2, $top = false) {

        $out = '<div id="dialog_bk"><img src="/images/dialog/dialog_bk.png"></div>
        <div id="dialog_head"><img src="/images/heads/'. $head .'.jpg"></div>
          <div id="dialog_content_up"><img src="/images/dialog/dialog_content_up.png">
            <div id="dialog_content_main"><span>'. $dialog1 .'</span>
              <div id="dialog_content_middle"></div>
                <div id="dialog_content_main2"><span>'. $dialog2 .'</span>
                  <img src="/images/dialog/dialog_content_down.png">
                </div>
            </div>
          </div>';

        if (!is_numeric($top) || $top === false) $out .= '<div style="height: 440px;"></div>';
        else $out .= '<div style="height: '. $top .'px;"></div>';
        echo $out;
    }


    /**
     * Является ли предмет заблокированный в квестах
     * @param $player Id игрока
     * @param $item Id предмета
     * @return string
     */
    public static function questItemIsBlocked($player, $item)
    {
        return Questblocks::model()->count("owner = :owner AND item = :item", array(':owner' => $player, ':item' => $item));
    }

    public static function diff (DateTime $firstDate, DateTime $secondDate){
        $firstDateTimeStamp = $firstDate->format("U");
        $secondDateTimeStamp = $secondDate->format("U");
        $rv = ($secondDateTimeStamp - $firstDateTimeStamp);
        $di = new DateIntervalCustom($rv);
        return $di;
    }

    public static function dateDiff(DateTime $date){
        $phrases = array(
          'patterns' => array(
            array(
              'Сегодня в %s',
              'Завтра, %s',
              'Послезавтра, %s',
              'Через %s',
              'Через %s %s'
            ),
            array(
              'Сегодня, %s',
              'Вчера, %s',
              'Позавчера, %s',
              '%s назад',
              '%s %s назад'
            )),
          'hours' => array(
            'час',
            'часа',
            'часов'
          ),
          'minutes' => array(
            'минуту',
            'минуты',
            'минут'
          ));

        $now = new DateTime();
        $interval = Tool::diff($now, $date);

        $diffYears  = abs($date->format('y') - $now->format('y'));
        $diffMonths = abs($date->format('m') - $now->format('m'));
        $diffDays   = abs($date->format('d') - $now->format('d'));
        $patterns   = $phrases['patterns'][$interval->invert];

        if (!$diffYears && !$diffMonths) {
            switch ($diffDays) {
                case 0:
                    switch ($interval->h) {
                        case 0:
                            return sprintf($patterns[3],
                              self::declension($interval->i, $phrases['minutes'])
                            );
                        case 1:
                            return sprintf($patterns[4],
                              self::declension($interval->h, $phrases['hours']),
                              self::declension($interval->i, $phrases['minutes'])
                            );
                    }
                    return sprintf($patterns[0],
                      $date->format('H:i:s')
                    );
                case 1:
                    return sprintf($patterns[1],
                      $date->format('H:i:s')
                    );
                case 2:
                    return sprintf($patterns[2],
                      $date->format('H:i:s')
                    );
            }
        }
        return $date->format('d.m.Y H:i:s');
    }

    public static function declension($number, $titles) {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $number . ' ' . $titles[($number%100 > 4 && $number%100 < 20)
          ? 2 : $cases[min($number%10, 5)]];
    }
}