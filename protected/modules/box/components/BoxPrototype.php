<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.10.16
 * Time: 12:22
 */
class BoxPrototype {
	/** @var Equipment */
	public $equipment;
	/** @var Players */
	public $player;
	public $params = [];

	public function __construct(Equipment $equipment, $params){
		$this->player = Yii::app()->stat->model;
		$this->equipment = $equipment;

		if (is_array($params)){
			foreach ($params as $key => $value){
				if(property_exists($this, $key)){
					$this->$key = $value;
				}
			}
		}
		$this->params = $params;
	}

	public function use(){
		return '';
	}
}