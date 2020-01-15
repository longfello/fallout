<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_invite_group_platinum extends logdata_prototype {
    public $fields = array(
        'amount', 'count'
    );
    public $message ='Вам зачисленно <b>%s</b> <img src="/images/platinum.png" border="0"> за отправку <b>%s</b> приглашений';
    public $type = 'invite_group_platinum';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $amount, $count){
        $data = array(
            'amount' => $amount,
            'count' => $count
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }
}