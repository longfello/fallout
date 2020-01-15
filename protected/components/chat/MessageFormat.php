<?php

class MessageFormat
{
    /**
     * Форматирование сообщения
     * @param $str string Исходное сообщение
     * @param $isRadio
     * @return string
     */
    public function format($str, $isRadio = false)
    {
        $str = mb_substr($str, 0, 400);

        if (!$isRadio)
            $str = strtr($str, array('<' => '&lt;', '>' => '&gt;'));

	    $str = str_replace('&nbsp;', ' ', $str);

        $wordsArray = $this->_transfer($str);

        $newArray = array();
        foreach ($wordsArray as $word)
        {
            $newArray[] = $this->_messageLinkToTag($word);
        }
        $message = implode(' ', $newArray);

        // Добавим смайлы в виде тэга
        $message = $this->_symbolToSmile($message);
        // Заменим BBCode на тэги
        // $message = $this->_bbCode($message);
        $message = $this->bb_parse($message);


        return $message;
    }


    /**
     * Перенос слова, если оно слишком длинное
     * Удаление лишних пробелов между словами
     * Замена html на спецсимволы
     * @param $str string Исходная строка
     * @return array
     */
    private function _transfer($str)
    {
        $maxWordLength = 70;

        $strArray = explode(' ', $str);
        $newArray = array();
        foreach ($strArray as $word)
        {
            if (mb_strlen($word) > $maxWordLength && !$this->_isUrl($word))
            {
                foreach ($this->_strSplitUnicode($word, $maxWordLength) as $one)
                    $newArray[] = $one;
            }
            else {
                $newArray[] = $word;
            }
        }

        return $newArray;
    }


    /**
     * Разбить строка на массив
     * @param $str string Входная строка
     * @param $l integer Количество символом после сколько разбивать
     * @return array Мвссив строк
     */
    private function _strSplitUnicode($str, $l = 0) {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }


    /**
     * Если существует ссылка, заменить ее на тэг
     */
    private function _messageLinkToTag($word)
    {
        if ($this->_isUrl($word))
            return CHtml::link(t::get('Ссылка'), $word, array('target' => '_blank'));

        return $word;
    }


    /**
     * Является ли строка ссылкой
     */
    private function _isUrl($str)
    {
        $protocols = array('http://', 'https://', 'ftp://');

        foreach ($protocols as $protocol)
        {
            if (mb_strpos($str, $protocol) !== false)
                return true;
        }
    }


    /**
     * Заменить спецсимволы на смайлы
     * @param $str string Сообщение
     * @return string
     */
    private function _symbolToSmile($str)
    {
        $smiles = array(
            // Основные смайлы
            "!:)" => '<img src="/images/smileys/1.gif">',
            "!:-)" => '<img src="/images/smileys/1.gif">',
            "!:(" => '<img src="/images/smileys/2.gif">',
            "!:-(" => '<img src="/images/smileys/2.gif">',
            "!:D" => '<img src="/images/smileys/3.gif">',
            "!:-D" => '<img src="/images/smileys/3.gif">',
            "!:o" => '<img src="/images/smileys/4.gif">',
            "!:-o" => '<img src="/images/smileys/4.gif">',
            "!:p" => '<img src="/images/smileys/5.gif">',
            "!:P" => '<img src="/images/smileys/5.gif">',
            "!:-p" => '<img src="/images/smileys/5.gif">',
            "!:-P" => '<img src="/images/smileys/5.gif">',
            "!O:-)" => '<img src="/images/smileys/7.gif">',
            "!;)" => '<img src="/images/smileys/6.gif">',
            "!;-)" => '<img src="/images/smileys/6.gif">',
            "!*ANGRY*" => '<img src="/images/smileys/angry.gif">',
            "!*DISCONTENT*" => '<img src="/images/smileys/nedovolstvo.gif">',
            "!:-[" => '<img src="/images/smileys/krasniy.gif">',
            "!8-)" => '<img src="/images/smileys/o4ki.gif">',
            "!ravno" => '<img src="/images/smileys/ravno.gif">',
            "!plus" => '<img src="/images/smileys/plus.gif">',
            "!minus" => '<img src="/images/smileys/minus.gif">',
            "*Daisy*" => '<img src="/images/smileys/daisy-angel.gif">',
            "*FLOWER*" => '<img src="/images/smileys/flower.gif">',
            "*NUKA*" => '<img src="/images/smileys/nuka.gif">',
            "*DEVIL*" => '<img src="/images/smileys/devil.gif">',
            "*LOL*" => '<img src="/images/smileys/laugh.gif">',
            "*BYE2*" => '<img src="/images/smileys/bye2.gif">',
            // Скрытые смайлы
            'O:-)' => '<img src="/images/smilies/aa.gif" alt="O:-)">',
            ':)' => '<img src="/images/smilies/ab.gif" alt=":)">',
            ':(' => '<img src="/images/smilies/ac.gif" alt=":(">',
            ';)' => '<img src="/images/smilies/ad.gif" alt=";)">',
            ':-P' => '<img src="/images/smilies/ae.gif" alt=":-P">',
            '8-)' => '<img src="/images/smilies/af.gif" alt="8-)">',
            ':-D' => '<img src="/images/smilies/ag.gif" alt=":-D">',
            ':-[' => '<img src="/images/smilies/ah.gif" alt=":-[">',
            'o_O' => '<img src="/images/smilies/ai.gif" alt="o_O">',
            ':-*' => '<img src="/images/smilies/aj.gif" alt=":-*">',
            ":((" => '<img src="/images/smilies/ak.gif" alt=":((">',
            ':-X' => '<img src="/images/smilies/al.gif" alt=":-X">',
            '-:o' => '<img src="/images/smilies/am.gif" alt="-:o">',
            ':-|' => '<img src="/images/smilies/an.gif" alt=":-|">',
            ':-/' => '<img src="/images/smilies/ao.gif" alt=":-/">',
            '*JOKINGLY*' => '<img src="/images/smilies/ap.gif" alt="*JOKINGLY*">',
            '*DIABLO*' => '<img src="/images/smilies/aq.gif" alt="*DIABLO*">',
            '*MUZ*' => '<img src="/images/smilies/ar.gif" alt="*MUZ*">',
            '*KISSED*' => '<img src="/images/smilies/as.gif" alt="*KISSED*">',
            ':-!' => '<img src="/images/smilies/at.gif" alt=":-!">',
            '*TIRED*' => '<img src="/images/smilies/au.gif" alt="*TIRED*">',
            '*STOP*' => '<img src="/images/smilies/av.gif" alt="*STOP*">',
            '*KISSING*' => '<img src="/images/smilies/aw.gif" alt="*KISSING*">',
            '*ROSE*' => '<img src="/images/smilies/ax.gif" alt="*ROSE*">',
            '*THUMBS UP*' => '<img src="/images/smilies/ay.gif" alt="*THUMBS UP*">',
            '*DRINK*' => '<img src="/images/smilies/az.gif" alt="*DRINK*">',
            '*IN LOVE*' => '<img src="/images/smilies/ba.gif" alt="*IN LOVE*">',
            '@=' => '<img src="/images/smilies/bb.gif" alt="@=">',
            '*HELP*' => '<img src="/images/smilies/bc.gif" alt="*HELP*">',
            '\m/' => '<img src="/images/smilies/bd.gif" alt="\m/">',
            '%-)' => '<img src="/images/smilies/be.gif" alt="%-)">',
            '*OK*' => '<img src="/images/smilies/bf.gif" alt="*OK*">',
            '*WASSUP*' => '<img src="/images/smilies/bg.gif" alt="*WASSUP*">',
            '*SORRY*' => '<img src="/images/smilies/bh.gif" alt="*SORRY*">',
            '*BRAVO*' => '<img src="/images/smilies/bi.gif" alt="*BRAVO*">',
            '*ROFL*' => '<img src="/images/smilies/bj.gif" alt="*ROFL*">',
            '*PARDON*' => '<img src="/images/smilies/bk.gif" alt="*PARDON*">',
            '*NO*' => '<img src="/images/smilies/bl.gif" alt="*NO*">',
            '*CRAZY*' => '<img src="/images/smilies/bm.gif" alt="*CRAZY*">',
            '*DONT_KNOW*' => '<img src="/images/smilies/bn.gif" alt="*DONT_KNOW*">',
            '*DANCE*' => '<img src="/images/smilies/bo.gif" alt="*DANCE*">',
            '*YAHOO*' => '<img src="/images/smilies/bp.gif" alt="*YAHOO*">',
            '*HI*' => '<img src="/images/smilies/bq.gif" alt="*HI*">',
            '*BYE*' => '<img src="/images/smilies/br.gif" alt="*BYE*">',
            '*YES*' => '<img src="/images/smilies/bs.gif" alt="*YES*">',
            '*ACUTE*' => '<img src="/images/smilies/bt.gif" alt="*ACUTE*">',
            '*WALL*' => '<img src="/images/smilies/bu.gif" alt="*WALL*">',
            '*WRITE*' => '<img src="/images/smilies/bv.gif" alt="*WRITE*">',
            '*SCRATCH*' => '<img src="/images/smilies/bw.gif" alt="*SCRATCH*">',
            '*FRIENDS*' => '<img src="/images/smilies/friends.gif" alt="*FRIENDS*">',
            '*PUNISH*' => '<img src="/images/smilies/punish.gif" alt="*PUNISH*">',
            '*TAKE_EXAMPLE*' => '<img src="/images/smilies/take_example.gif" alt="*TAKE_EXAMPLE*">',
            '*BLACK_EYE*' => '<img src="/images/smilies/black_eye.gif" alt="*BLACK_EYE*">',
            '*IREFUL*' => '<img src="/images/smilies/ireful.gif" alt="*IREFUL*">',
            '*BOAST*' => '<img src="/images/smilies/boast.gif" alt="*BOAST*">',
            '*GAUSSWIN*' => '<img src="/images/smilies/gauss-win.gif" alt="*GAUSSWIN*">',
            '*COOLPILOT*' => '<img src="/images/smilies/coolpilot.gif" alt="*COOLPILOT*">',
            '*COMBATARMOR*' => '<img src="/images/smilies/combatarmor.gif" alt="*COMBATARMOR*">',
            '*BOMB*' => '<img src="/images/smilies/bomb.gif" alt="*BOMB*">',
            '*GAUSS*' => '<img src="/images/smilies/gauss.gif" alt="*GAUSS*">',
            '*SULIK*' => '<img src="/images/smilies/sulik.gif" alt="*SULIK*">',
            '*SAD*' => '<img src="/images/smilies/sad.gif" alt="*SAD*">',
            '*DANGER*' => '<img src="/images/smilies/nuke-danger.gif" alt="*DANGER*">',
            '*FIGHTER*' => '<img src="/images/smilies/fighter.gif" alt="*FIGHTER*">',
            '*FACEPALM* ' => '<img src="/images/smilies/facepalm.gif" alt="*FIGHTER*">',
            '*HERCULES*' => '<img src="/images/smilies/bb2.gif" alt="*HERCULES*">',
            '*PEACE*' => '<img src="/images/smilies/flag_of_truce.gif" alt="*PEACE*">',
            '*HAT*' => '<img src="/images/smilies/hi_hat.gif" alt="*HAT*">',
            '*RAKE*' => '<img src="/images/smilies/hiteoid_2.gif" alt="*RAKE*">',
            '*CUTE*' => '<img src="/images/smilies/js_flirt.gif" alt="*CUTE*">',
            '*HANDSHAKE*' => '<img src="/images/smilies/js_handshake.gif" alt="*HANDSHAKE*">',
            '*BAN*' => '<img src="/images/smilies/kidrock_01.gif" alt="*BAN*">',
            '*BAYAN*' => '<img src="/images/smilies/laie_48.gif" alt="*BAYAN*">',
			'*POP*' => '<img src="/images/smilies/pop.gif" alt="*POP*">',
            '*POPCORN*' => '<img src="/images/smilies/popcorn2.gif" alt="*POPCORN*">',
            '*POPKA*' => '<img src="/images/smilies/popka.gif" alt="*POPKA*">',
            '*PRAYER*' => '<img src="/images/smilies/prayer.gif" alt="*PRAYER*">',
            '*AGREEMENT*' => '<img src="/images/smilies/rtfm.gif" alt="*AGREEMENT*">',
            '*RUSSIAN*' => '<img src="/images/smilies/russian_ru.gif" alt="*RUSSIAN*">',
            '*SARCASTIC*' => '<img src="/images/smilies/sarcastic.gif" alt="*SARCASTIC*">',
            '*CRY*' => '<img src="/images/smilies/smile12.gif" alt="*CRY*">',
            '*SPITEFUL*' => '<img src="/images/smilies/spiteful.gif" alt="*SPITEFUL*">',
            '*BSMILE*' => '<img src="/images/smilies/boy_smile.gif" alt="*BSMILE*">',
            '*BSAD*' => '<img src="/images/smilies/boy_sad.gif" alt="*BSAD*>"',
            '*BLAUGHTER*' => '<img src="/images/smilies/boy_laughter.gif" alt="*BLAUGHTER*">',
            '*BSURPRISE*' => '<img src="/images/smilies/boy_surprise.gif" alt="*BSURPRISE*">',
            '*BGIBE*' => '<img src="/images/smilies/boy_gibe.gif" alt="*BGIBE*">',
            '*BWINK*' => '<img src="/images/smilies/boy_wink.gif" alt="*BWINK*">',
            '*BANGER*' => '<img src="/images/smilies/boy_anger.gif" alt="*BANGER*">',
            '*BAPATHY*' => '<img src="/images/smilies/boy_apathy.gif" alt="*BAPATHY*">',
            '*BUNEASINESS*' => '<img src="/images/smilies/boy_uneasiness.gif" alt="*BUNEASINESS*">',
            '*BSLOPE*' => '<img src="/images/smilies/boy_slope.gif" alt="*BSLOPE*">',
            '*SANTA*' => '<img src="/images/smileys/santa-1.gif" alt="*SANTA*">',
            '*SANTA_HI*' => '<img src="/images/smileys/santa-2.gif" alt="*SANTA_HI*">',
            '*SANTA_HOHO*' => '<img src="/images/smileys/santa-3.gif" alt="*SANTA_HOHO*">'
        );


        return strtr($str, $smiles);
    }


    /**
     * Преобразование BBCode в соответствующие тэги
     */
    private function _bbCode($str)
    {
        $bbCode = array(
            "[b]" => "<b>",
            "[u]" => "<u>",
            "[i]" => "<i>",
            "[s]" => "<s>",
            "[/b]" => "</b>",
            "[/u]" => "</u>",
            "[/i]" => "</i>",
            "[/s]" => "</s>"
        );

        $str = strtr($str, $bbCode);

    }

	function bb_parse($string) {
		$tags = 'b|u|i|s|player|quote';
		while (preg_match_all('`\[('.$tags.')=?(.*?)\](.*?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) {
			list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
			$replacement = false;
			switch ($tag) {
				case 'b': $replacement = "<strong>$innertext</strong>"; break;
				case 'i': $replacement = "<em>$innertext</em>"; break;
				case 'u': $replacement = "<u>$innertext</u>"; break;
				case 's': $replacement = "<s>$innertext</s>"; break;
				case 'quote': $replacement = "<blockquote>$innertext</blockquote>" . $param? "<cite>$param</cite>" : ''; break;
				case 'player':
					$id = (int)$param;
					$model = Players::model()->findByPk($id);
					if ($model) {
						$data = ChatPlayer::getRenderPlayerData($model->id);
						$replacement = "<span data-user-id='{$data['id']}' class='nick {$data['style']} u{$data['id']}'>{$data['user']}</span>";
					} else $replacement = ' '; //'<a href="' . ($param? $param : $innertext) . "\">$innertext</a>";
					break;
			}
			$string = str_replace($match, $replacement, $string);
		}
		return $string;
	}

}