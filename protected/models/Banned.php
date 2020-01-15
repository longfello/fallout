<?php

/**
 * This is the model class for table "banned".
 *
 * The followings are the available columns in table 'banned':
 * @property integer $id
 * @property string $user
 * @property string $ip
 * @property string $chat
 * @property string $forums
 * @property string $site
 * @property integer $userid
 * @property string $bandate
 * @property integer $tor
 */
class Banned extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'banned';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user', 'required'),
			array('userid, tor', 'numerical', 'integerOnly'=>true),
			array('user', 'length', 'max'=>15),
			array('ip', 'length', 'max'=>50),
			array('chat, forums, site', 'length', 'max'=>3),
			array('bandate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user, ip, chat, forums, site, userid, bandate, tor', 'safe', 'on'=>'search'),
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
			'user' => 'Ник персонажа из players',
			'ip' => 'IP игрока (при бане из админки берется из players)',
			'chat' => 'Бан в чате (Yes, No)',
			'forums' => 'Бан на форуме (Yes, No, по состоянию на 09.10.2011 не используется)',
			'site' => 'Бан в игре (Yes, No)',
			'userid' => 'ID персонажа из players',
			'bandate' => 'Временный бан (число, до которого бан)',
			'tor' => 'ip является точкой выхода TOR',
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
		$criteria->compare('user',$this->user,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('chat',$this->chat,true);
		$criteria->compare('forums',$this->forums,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('bandate',$this->bandate,true);
		$criteria->compare('tor',$this->tor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Banned the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
