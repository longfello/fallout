<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_invite_person_platinum extends logdata_prototype {
    public $fields = array(
        'amount', 'email' 
    );
    public $message ='Вам зачисленно <b>%s</b> <img src="/images/platinum.png" border="0"> за отправку приглашения на email <b>%s</b>';
    public $type = 'invite_person_platinum';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $amount, $email){
        $data = array(
            'amount' => $amount,
            'email' => $email
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }
}