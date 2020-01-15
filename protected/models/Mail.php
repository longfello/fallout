<?php

/**
 * This is the model class for table "mail".
 *
 * The followings are the available columns in table 'mail':
 * @property integer $id
 * @property string $sender
 * @property string $subject
 * @property string $body
 * @property string $unread
 * @property string $kbox
 * @property integer $senderid
 * @property integer $owner
 * @property string $senderdel
 * @property string $ownerdel
 * @property string $dt
 */
class Mail extends CActiveRecord
{
	public $unreadTypes = [
		'T' => 'Да',
		'F' => 'Нет',
	];

	public $kboxTypes = [
		'Y' => 'Да',
		'N' => 'Нет',
	];

	public $dt_start;
	public $dt_end;
	public $player_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('body', 'required'),
			array('senderid, owner', 'numerical', 'integerOnly'=>true),
			array('sender', 'length', 'max'=>15),
			array('subject', 'length', 'max'=>50),
			array('unread, kbox, senderdel, ownerdel', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sender, subject, body, unread, kbox, senderid, owner, senderdel, ownerdel, dt, dt_start, dt_end, player_id', 'safe', 'on'=>'search'),
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
			'ownerModel' => array(self::BELONGS_TO, 'Players', 'owner'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender' => 'Отправитель',
			'subject' => 'Тема',
			'body' => 'Письмо',
			'unread' => 'Непрочитанное',
			'kbox' => 'Сохранённое',
			'senderid' => 'Id отправителя',
			'owner' => 'Владелец',
			'senderdel' => 'Метка удаления отправителем',
			'ownerdel' => 'Метка удаления адресатом',
			'dt' => 'Дата отправки',
			'dt_start' => 'Дата отправки (от)',
			'dt_end' => 'Дата отправки (до)',
			'player_id' => 'Id игрока'
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
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('unread',$this->unread,true);
		$criteria->compare('kbox',$this->kbox,true);
		$criteria->compare('senderid',$this->senderid);
		$criteria->compare('owner',$this->owner);
		$criteria->compare('senderdel',$this->senderdel,true);
		$criteria->compare('ownerdel',$this->ownerdel,true);
		$criteria->compare('dt',$this->dt,true);

		if (!is_null($this->player_id) && is_numeric($this->player_id)){
			$criteria->addCondition("senderid = {$this->player_id} OR owner = {$this->player_id}");
		}

		if ($this->dt_start && !is_null($this->dt_start)) {
			$tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
			$criteria->addCondition("dt>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
		}

		if ($this->dt_end && !is_null($this->dt_end)) {
			$tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
			$criteria->addCondition("dt<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 50
			),
			'sort'=>array(
				'defaultOrder'=>'dt DESC',
			)
		));
	}

	public function getUnreadName(){
		return isset($this->unreadTypes[$this->unread])?$this->unreadTypes[$this->unread]:'?';
	}

	public function getKboxName(){
		return isset($this->kboxTypes[$this->kbox])?$this->kboxTypes[$this->kbox]:'?';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
