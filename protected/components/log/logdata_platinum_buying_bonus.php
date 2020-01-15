<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 16.05.16
 * Time: 13:58
 */
class logdata_platinum_buying_bonus extends logdata_prototype {
  public $fields = array('id');
  public $message ='В качестве дополнительного бонуса в ваш личный инвентарь выдан предмет: %s. Администрация';
  public $type = 'platinum_buying_bonus';

  public static function add(){
    $args = func_get_args();
    $class = get_called_class();
    $model = new $class();
    call_user_func_array(array($model, '__add'), $args);
  }

  public function __add($to_id, $equipment_id){
    self::save($to_id, Log::CATEGORY_ETC , array('id' => $equipment_id));
  }

	public function render($data) {
		$model = Equipment::model()->findByPk($data['id']);
		/** @var $model Equipment */
		if ($model) {
			$data['id'] = $model->getML('name');
		} else {
			$data['id'] = '?';
		}
		return parent::render($data);
	}

}