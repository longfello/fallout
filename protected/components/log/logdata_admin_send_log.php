<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_admin_send_log extends logdata_prototype {
    public $fields = array(
        'message', 'message_en', 'sender', 'sender_en'
    );
    public $message ='%s %s';
    public $type = 'admin_send_log';

    public static function add(){
        $args = func_get_args();
        $class = get_called_class();
        $model = new $class();
        call_user_func_array(array($model, '__add'), $args);
    }

    public function __add($to_id, $message, $message_en, $sender, $sender_en){
        $data = array(
            'message' => trim($message),
            'message_en' => trim($message_en),
            'sender' => $sender,
            'sender_en' => $sender_en,
        );
        self::save($to_id, Log::CATEGORY_ETC , $data);
    }

    public function render($data) {
        if (t::iso()=='ru') {
            $message = sprintf($this->message, $data['message'], $data['sender']);
        } else {
            $message = sprintf($this->message, $data['message_en'], $data['sender_en']);
        }
        return $message;
    }
}