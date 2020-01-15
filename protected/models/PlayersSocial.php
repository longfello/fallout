<?php

/**
 * This is the model class for table "players_social".
 *
 * The followings are the available columns in table 'players_social':
 * @property integer $id
 * @property integer $player_id
 * @property string $provider
 * @property string $identity
 * @property string $profileURL
 * @property string $photoURL
 * @property string $email
 * @property string $displayName
 */
class PlayersSocial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'players_social';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, provider, identity', 'required'),
			array('player_id', 'numerical', 'integerOnly'=>true),
			array('provider', 'length', 'max'=>50),
			array('identity', 'length', 'max'=>100),
			array('profileURL, photoURL, email, displayName', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, player_id, provider, identity, profileURL, photoURL, email, displayName', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'player_id' => 'Player',
			'provider' => 'Provider',
			'identity' => 'Identity',
			'profileURL' => 'Profile Url',
			'photoURL' => 'Photo Url',
			'email' => 'Email',
			'displayName' => 'Display Name',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('player_id',$this->player_id);
		$criteria->compare('provider',$this->provider,true);
		$criteria->compare('identity',$this->identity,true);
		$criteria->compare('profileURL',$this->profileURL,true);
		$criteria->compare('photoURL',$this->photoURL,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('displayName',$this->displayName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlayersSocial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
