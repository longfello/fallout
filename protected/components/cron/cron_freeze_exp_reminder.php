<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 15.11.16
 * Time: 12:52
 */
class cron_freeze_exp_reminder extends cron_prototype {
    public $name = 'Напоминиание о завершении заморозки опыта';
	public $player_id = false;
	public $date = false;

	public function run(){
		if ($this->player_id && $model = Players::model()->findByPk($this->player_id)){
			echo('Send popup to player '.$model->id.PHP_EOL);
			t::getInstance()->setLanguage($model->lang_slug);
			Popup::add($model->id, t::get("freeze-exp-reminder-text %s", [$this->date]));
		}
	}
}