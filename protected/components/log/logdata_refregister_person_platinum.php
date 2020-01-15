<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_refregister_person_platinum extends logdata_prototype {
    public $fields = array(
        'level', 'id', 'name', 'amount',
    );
    public $message ='За достижение %s-го уровня приглашенным вами игроком <b><a href="/view.php?view=%s">%s</a></b> Вам начисленно <b>%s</b> <img src="/images/platinum.png" border="0">';
    public $type = 'refregister_person_platinum';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $level, $id, $name, $amount){
        $data = array(
            'amount' => $amount,
            'id' => $id,
            'name' => $name,
            'level' => $level
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }
}