<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_remove_item extends logdata_prototype {
    public $fields = array(
        'place', 'item', 'count'
    );
    public $message ='Из %s изъяты ресурсы: %s х %s шт. Администрация';
    public $type = 'admin_remove_item';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $place, $item_id, $count){
        $data = array(
            'place' => $place,
            'item' => $item_id,
            'count' => $count
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }

    public function render($data) {
        $model = Equipment::model()->findByPk($data['item']);
        if ($model) {
            /** @var $model Equipment */
            $place_log = '';
            switch($data['place']){
                case 'inv':
                    $place_log = t::get('вашего личного инвентаря');
                    break;
                case 'store':
                    $place_log = t::get('вашей банковской кладовки');
                    break;
            }

            $data['place'] = $place_log;
            $data['item'] = $model->getML('name');
            return parent::render($data);
        } else return '????';
    }
}