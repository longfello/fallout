<?php

class Item
{
    /**
     * Получить абсолютный путь до картинки предмета
     * @param string $type Тип предмета
     * @param string $imageName Наименование картинки, взятое из базы
     * @return string Абсолютный путь к изображению предмета
     */
    public static function getImageUrl($type, $imageName)
    {
        switch($type)
        {
            case "D":
                $img = "food";
                $small= "";
                break;
            case "F":
                $img = "food";
                $small= "";
                break;
            case "A":
                $img = "rmr";
                $small="small";
                break;
            case "W":
                $img = "wpn";
                $small="small";
                break;
            case "G":
                $img = "gifts";
                $small="";
                break;
            case "U":
                $img = "misc";
                $small="";
                break;
            default:
                $img = "misc";
                $small="";
                break;
        }

        return "images/{$img}/{$imageName}{$small}.png";
    }


    /**
     * Получить наименование типа предмета
     * @param string $type Тип предмета
     * @return string Наименование типа предмета
     */
    public static function getTypeName($type)
    {
        switch($type)
        {
            case "D":
                $typeName = t::get("Препарат/Напиток");
                break;
            case "F":
                $typeName = t::get("Продукт");
                break;
            case "A":
                $typeName = t::get("Броня");
                break;
            case "W":
                $typeName = t::get("Оружие");
                break;
            case "G":
                $typeName = t::get("Подарок");
                break;
            case "U":
                $typeName = t::get("Оборудование");
                break;
            default:
                $typeName = t::get("Предмет");
                break;
        }

        return $typeName;
    }


    /**
     * Доступен ли предмет для покупки
     * @param object $item Данные о предмете
     * @return boolean True в случае доступности, false в случае недоступности
     */
    public static function isAvailable($item)
    {
        $available = true;

        if (Yii::app()->stat->level < $item->minlev)
        {
            $available = false;
        }

        if ($item->clan_id && Yii::app()->stat->clan != $item->clan_id)
        {
            $available = false;
        }

        return $available;
    }


    /**
     * Получить требования к ношению или покупке предмета предмета
     * @param $item Данные о предмете
     * @return array
     */
    private function _getRequirements($item)
    {
        $requirements = array();
        $msg = array();

        // Подходит ли уровень
        if($item->minlev > 1)
        {
            $msg = array(
                'param' => t::get('Уровень'),
                'value' => $item->minlev
            );

            if (Yii::app()->stat->level < $item->minlev)
            {
                $requirements[]['deny'] = $msg;
            }
            else
            {
                $requirements[]['access'] = $msg;
            }
        }

        // Принадлежит ли предмет клану
        if ($item->clan_id)
        {
            $msg = array(
                'param' => t::get('Клан'),
                'value' => '<img src="images/clans/' . $item->clan_id . '.gif">'
            );

            if ($item->clan_id != Yii::app()->stat->clan)
            {
                $requirements[]['deny'] = $msg;
            }
        }


        return $requirements;
    }

    /**
     * Получить контент для всплывающего окна
     * @param object $item Данные о предмете
     * @return string
     */
    public static function getPopupContent($item)
    {
	    $equipment = Equipment::model()->findByPk($item->id);
	    $goodstyle = 'style="color:lightblue"';
        $badstyle = 'style="color:red;"';

        $html = '<table style="width:300px;margin:0px;padding:0px;" cellspacing=0 cellpadding=2 border=0>';
        $html .= '<tr><td colspan="2" align="center">' . self::getTypeName($item->type) . ' <b>' . $item->name . '</b></td></tr>';

        $html .= '<tr><td align="center" valign="middle">';
        $html .= CHtml::image($equipment->getPicture(Equipment::IMAGE_SMALL));
        $html .= '</td>';
        $html .= '<td valign="top">';
        $html .= '<table border=0 width=100% cellspacing=1 cellpadding=1>';

        // Параметры, которые добавляет/отнимает предмет при использовании
        if ($params = self::_getParams($item))
        {
            foreach ($params as $msg)
            {
                if (key($msg) == 'plus')
                    $html .= "<tr><td>" . $msg['plus']['param'] . "</td><td " . $goodstyle . " align=right><b>" . $msg['plus']['value'] . "</b></td></tr>";
                else
                    $html .= "<tr><td>" . $msg['minus']['param'] . "</td><td " . $badstyle . " align=right><b>" . $msg['minus']['value'] . "</b></td></tr>";
            }
        }

        $html .= "<tr><td colspan=2><hr></td></tr>";
        $html .= "<tr><td colspan=2 align=center><b>".t::get('требования')."</b></td></tr>";


        // Если есть требования к предмету
        if ($requirements = self::_getRequirements($item))
        {
            foreach ($requirements as $type => $req)
            {
                if (key($req) == 'access')
                    $html .= "<tr><td>" . $req['access']['param'] . "</td><td " . $goodstyle . " align=right><b>" . $req['access']['value'] . "</b></td></tr>";
                else
                    $html .= "<tr><td " . $badstyle . ">" . $req['deny']['param'] . "</td><td " . $badstyle . " align=right><b>" . $req['deny']['value'] . "</b></td></tr>";
            }
        }
        else {
            $html .= "<tr><td colspan=2 align=center>".t::get('нет')."</td></tr>";
        }

        $html .= "</table></td></tr>";
        $html .= "</table>";

        return $html;
    }


    /**
     * Получить параметры, которые даёт предмет при его использовании
     */
    private function _getParams($item)
    {
        $paramMessages = array();

        $paramNames = array(
            'strength'    => t::get('Сила'),
            'agility'     => t::get('Ловкость'),
            'defense'     => t::get('Защита'),
            'max_hp'      => t::get('Макс. здоровье'),
            'max_energy'  => t::get('Макс.&nbsp;энергия'),
            'hp'          => t::get('Здоровье'),
            'energy'      => t::get('Энергия'),
            'pohod'       => t::get('Походные очки')
        );

        foreach ($paramNames as $name => $value)
        {
            $addedValue = $item->{"add_" . $name};
            if ($addedValue > 0)
            {
                $paramMessages[]['plus'] = array('param' => $value, 'value' => '+' . $addedValue);
            }
            elseif ($addedValue < 0)
            {
                $paramMessages[]['minus'] = array('param' => $value, 'value' => $addedValue);
            }
        }

        return $paramMessages;
    }
}