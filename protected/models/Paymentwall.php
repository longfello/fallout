<?php

/**
 * This is the model class for table "paymentwall".
 *
 * The followings are the available columns in table 'paymentwall':
 * @property string $order_id
 * @property integer $amount
 * @property integer $user_id
 * @property string $ref
 * @property string $pay_time
 */
class Paymentwall extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'paymentwall';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amount, user_id', 'required'),
			array('amount, user_id', 'numerical', 'integerOnly'=>true),
			array('ref', 'length', 'max'=>255),
			array('pay_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('order_id, amount, user_id, ref, pay_time', 'safe', 'on'=>'search'),
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
			'order_id' => '#оплаты',
			'amount' => 'Крышек',
			'user_id' => 'ID игрока',
			'ref' => 'Идентификатор платежной системы',
			'pay_time' => 'Время прихода уведомления об оплате',
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

		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ref',$this->ref,true);
		$criteria->compare('pay_time',$this->pay_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 50 //edit your number items per page here
              ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Paymentwall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
