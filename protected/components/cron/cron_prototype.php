<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 15.11.16
 * Time: 12:47
 */
class cron_prototype {
	public $name = 'prototype';

	public function __construct($params = ''){
		if ($this->isJson($params)){
			$params = json_decode($params);
		}
		if (is_array($params) || is_object($params)){
			foreach ($params as $key => $value){
				if (isset($this->$key)){
					$this->$key = $value;
				}
			}
		}
	}

	public function run(){
		echo('Running prototype...');
	}

	/**
	 * @param $when int Timestamp
	 * @param $params array
	 */
	public static function schedule($when, $params){
		$model = new CronJobs();
		$model->run_after  = date(DateTime::W3C, $when);
		$model->params     = json_encode($params);
		$model->class_name = get_called_class();
		$model->save();
	}

	private function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}