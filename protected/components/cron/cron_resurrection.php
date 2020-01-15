<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 15.11.16
 * Time: 12:52
 */
class cron_resurrection extends cron_prototype {
    public $name = 'Перерождение в гуля';
	public $player_id = false;
	public $layout = [];

	public function run(){
		if ($this->player_id && $player = Players::model()->findByPk($this->player_id)){
			$player->race_id = PlayerRace::RACE_GULE;
			$player->save(false);

			if ($this->layout){
				if (is_array($this->layout) || is_object($this->layout)) {

					PlayerAppearance::model()->deleteAllByAttributes([
						'player_id' => $player->id
					]);

					foreach ( $this->layout as $key => $value ) {
						switch ( $key ) {
							case 'gender':
								if ( in_array( $value, [ Players::GENDER_MALE, Players::GENDER_FEMALE ] ) ) {
									$player->gender = $value;
									$player->save( false );
								}
								break;
							default:
								$layout     = AppearanceLayout::model()->findByPk( $key );
								$valueModel = PlayerRaceAppearanceList::model()->findByPk( $value );
								if ( $layout && $valueModel && $valueModel->appearance_layout_id == $layout->id ) {
									$model                            = new PlayerAppearance();
									$model->player_id                 = $player->id;
									$model->appearance_layout_id      = $layout->id;
									$model->player_race_appearance_id = $valueModel->id;
									$model->save();
								}
						}
					}
				}
			}
			echo('Player '.$player->id.' now GULE'.PHP_EOL);
			t::getInstance()->setLanguage($player->lang_slug);
			Popup::add($player->id, t::get("Персонаж был перерожден в гуля."));
			logdata_gule_resurrection::add($player->id);
			//
		}
	}
}
