<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_remove_resource_gold extends logdata_prototype {
    public $fields = array(
        'place', 'count'
    );
    public $message ='У вас %s изъято золото: %s<img src="images/gold.png">. Администрация';
    public $type = 'admin_remove_resource_gold';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $place, $count){
        $data = array(
            'place' => $place,
            'count' => $count
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }

    public function render($data) {
        $place_log = '';
        switch($data['place']){
            case 'hand':
                $place_log = t::get('из инвентаря');
                break;
            case 'bank':
                $place_log = t::get('из банка');
                break;
            case 'toxbank':
                $place_log = t::get('из банка токсических пещер');
                break;
        }

        $data['place'] = $place_log;
        return parent::render($data);
    }
}