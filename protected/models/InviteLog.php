<?php

/**
 * This is the model class for table "{{invite_log}}".
 *
 * The followings are the available columns in table '{{invite_log}}':
 * @property string $id
 * @property integer $owner
 * @property string $email
 * @property integer $caps_send
 * @property integer $caps_reg
 * @property string $created_at
 * @property integer $unsubscribed
 */
class InviteLog extends CActiveRecord
{
    public $dt_start;
    public $dt_end;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{invite_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner, email, created_at', 'required'),
			array('owner, caps_send, caps_reg, unsubscribed', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner, email, caps_send, caps_reg, created_at, unsubscribed, dt_start, dt_end', 'safe', 'on'=>'search'),
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
			'owner' => 'Пригласивший',
			'email' => 'Email приглашенного',
			'caps_send' => 'Получено крышек за отправку',
			'caps_reg' => 'Получено крышек за регистрацию',
			'created_at' => 'Дата отправки',
			'unsubscribed' => 'Отписался',
            'dt_start' => 'Дата отправки (от)',
			'dt_end' => 'Дата отправки (до)'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('owner',$this->owner);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('caps_send',$this->caps_send);
		$criteria->compare('caps_reg',$this->caps_reg);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('unsubscribed',$this->unsubscribed);

        if ($this->dt_start && !is_null($this->dt_start)) {
            $tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
            $criteria->addCondition("created_at>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
        }

        if ($this->dt_end && !is_null($this->dt_end)) {
            $tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
            $criteria->addCondition("created_at<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'Pagination' => array (
                'PageSize' => 100
            ),
            'sort'=>array(
                'defaultOrder'=>'created_at DESC',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InviteLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function unsubscribe($email) {
        $query = "UPDATE rev_invite_log SET `unsubscribed`='1' WHERE email = '{$email}'";
        return Yii::app()->db->createCommand($query)->execute();
    }
}
