<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_give_clan_item extends logdata_prototype {
    public $fields = array(
        'item', 'count'
    );
    public $message ='В кладовку вашего клана выданы ресурсы: %s х %s шт. Администрация';
    public $type = 'admin_give_clan_item';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $item_id, $count){
        $data = array(
            'item' => $item_id,
            'count' => $count
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }

    public function render($data) {
        $model = Equipment::model()->findByPk($data['item']);
        if ($model) {
            /** @var $model Equipment */

            $data['item'] = $model->getML('name');
            return parent::render($data);
        } else return '????';
    }
}