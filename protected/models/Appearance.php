<?php

/**
 * This is the model class for table "appearance".
 *
 * The followings are the available columns in table 'appearance':
 * @property integer $user_id
 * @property string $avatar
 * @property string $hairstyle
 * @property string $beard
 * @property integer $free_set_used
 * @property integer $unique_avatar
 * @property string $free_race_change_until
 */
class Appearance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'appearance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, free_set_used, unique_avatar', 'numerical', 'integerOnly'=>true),
			array('avatar, hairstyle, beard', 'length', 'max'=>16),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, avatar, hairstyle, beard, free_set_used, unique_avatar, free_race_change_until', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'ID персонажа из players',
			'avatar' => 'Аватар из папки avatars/players/male (мужской) или female (женский)',
			'hairstyle' => 'Прическа из папки avatras/players/Hairstyle',
			'beard' => 'Борода из папки avatras/players/Beards',
			'free_set_used' => 'Использована ли бесплатная возможность сменить внешность: 0 - нет, 1 - да',
			'unique_avatar' => '0 - нет уникального аватара, 1 - уникальный аватар user_id.png из папки avatars/players/unique',
			'free_race_change_until' => 'До когда возможна бесплатная смена расы',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('hairstyle',$this->hairstyle,true);
		$criteria->compare('beard',$this->beard,true);
		$criteria->compare('free_set_used',$this->free_set_used);
		$criteria->compare('unique_avatar',$this->unique_avatar);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Appearance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getLayouts($player, $armor_id, $wpn_id){
		$appearance  = Appearance::model()->findByPk($player->id);
		$raceLayouts = PlayerAppearance::getLayouts($player->id);
		$armor       = Equipment::model()->cache( 15 * 60 )->findByPk( $armor_id );
		$weapon      = Equipment::model()->cache( 15 * 60 )->findByPk( $wpn_id );

		$layouts     = [];

		$isUniqueAvatar = (
			$appearance->unique_avatar
			&& file_exists( basedir . "/avatars/players/unique/{$player->id}.png" )
			&& file_exists( basedir . "/avatars/players/unique/hands/{$player->id}.png" )
		);

		/* Avatar + unique avatar */
		if ($isUniqueAvatar){
			$layouts['avatar'] = "/avatars/players/unique/{$player->id}.png";
		} else {
			$layouts['avatar'] = $raceLayouts['avatar']['layout'];
		}
		/* Hairstyle - if not unique avatar */
		if (!$isUniqueAvatar){
			$layouts['hairstyle'] = $raceLayouts['hairstyle']['layout'];
		}
		/* Beards - if not unique avatar */
		if (!$isUniqueAvatar){
			$layouts['bread'] = $raceLayouts['bread']['layout'];
		}
		/* Armor */
		if ( $armor ) {
			$layouts['armor'] = ( $player->gender == Players::GENDER_MALE ) ? $armor->getPicture( Equipment::IMAGE_ORIGINAL ) : $armor->getPicture( Equipment::IMAGE_FEMALE );
		}
		/* Weapon */
		if ( $weapon ) {
			$layouts['weapon'] = ( $player->gender == Players::GENDER_MALE ) ? $weapon->getPicture( Equipment::IMAGE_ORIGINAL ) : $weapon->getPicture( Equipment::IMAGE_FEMALE );
		}
		/* Hands + unique hands  */
		if ($isUniqueAvatar){
			$layouts['hands'] = "/avatars/players/unique/hands/{$player->id}.png";
		} else {
			$layouts['hands'] = $raceLayouts['avatar']['hands'];
		}
		/* Hands armor */
		if ( $armor ) {
			$layout = ( $player->gender == Players::GENDER_MALE ) ? $armor->getPicture( Equipment::IMAGE_HAND ) : $armor->getPicture( Equipment::IMAGE_HAND_FEMALE );
			if ( $layout ) {
				$layouts['hands_armor'] = $layout;
			}
		}
		/* Pets */
		if ( $player->pet && $player->pet->type ) {
			$layouts['pet'] = "/images/pets/{$player->pet->type}.png";
		}
		return $layouts;
	}
}
