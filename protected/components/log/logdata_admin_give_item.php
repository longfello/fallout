<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_give_item extends logdata_prototype {
    public $fields = array(
        'place', 'item', 'count'
    );
    public $message ='В %s выданы ресурсы: %s х %s шт. Администрация';
    public $type = 'admin_give_item';

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
                    $place_log = t::get('ваш личный инвентарь');
                    break;
                case 'store':
                    $place_log = t::get('вашу банковскую кладовку');
                    break;
            }

            $data['place'] = $place_log;
            $data['item'] = $model->getML('name');
            return parent::render($data);
        } else return '????';
    }
}