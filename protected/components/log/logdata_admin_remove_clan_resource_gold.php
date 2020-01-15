<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_remove_clan_resource_gold extends logdata_prototype {
    public $fields = array(
        'count'
    );
    public $message ='Из казны вашего клана изъято золото: %s<img src="images/gold.png">. Администрация';
    public $type = 'admin_remove_clan_resource_gold';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $count){
        $data = array(
            'count' => $count
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }

    public function render($data) {
        return parent::render($data);
    }
}