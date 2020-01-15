<?php

class RText
{

    /**
     * Обрезать строку по количеству слов
     *
     * @param $text
     * @param int $numOfWords
     * @param string $add
     * @return mixed|string
     */
    public static function truncateText($text, $numOfWords = 10, $add = ''){
        if($numOfWords){
            $text = strip_tags($text, '<br/>');
            $text = str_replace(array("\r", "\n"), '', $text);

            $lenBefore = strlen($text);
            if($numOfWords){
                if(preg_match("/(\S+\s*){0,$numOfWords}/", $text, $match))
                    $text = trim($match[0]);
                if(strlen($text) != $lenBefore){
                    $text .= ' ... '.$add;
                }
            }
        }

        return $text;
    }


    /**
     * Обрезать строку по заднной ширине
     */
    public static function truncateTextWidth($text, $width)
    {
        $text = strip_tags($text, '<br/>');
        $text = str_replace(array("\r", "\n"), '', $text);

        if (mb_strlen($text) > $width) {
            $position = mb_strpos($text, ' ', $width);
            $text = mb_strimwidth($text, 0, $position, '');
        }

        return $text . ' ...';
    }


    /**
     * Обрезать строку по количеству символов
     */
    public static function truncateTextCount($text, $count, $add)
    {
        $text = str_replace('&nbsp;', '', $text);
        $text = strip_tags($text, '<br/>');
        $text = str_replace(array("\r", "\n"), '', $text);
        $text = trim($text);

        if (mb_strlen($text) > $count) {
            $text = mb_substr($text, 0, $count);
            $pos = strrpos($text, ' '); // определяем позиция последнего пробела
            $text = substr($text, 0, $pos). $add; // обрезаем переменную по определенно выше позиции
        }


        return $text;
    }
}