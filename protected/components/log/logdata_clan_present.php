<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_clan_present extends logdata_prototype {
    public $fields = array(
        'clan_id', 'present_name'
    );
    public $message ='На <a href="/clans.php?view=view&id=%s">страницу</a> Вашего клана добавлена награда: %s. Администрация.';
    public $type = 'clan_present';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $clan_id, $present_name){
        $data = array(
            'clan_id' => $clan_id,
            'present_name' => $present_name
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }
}