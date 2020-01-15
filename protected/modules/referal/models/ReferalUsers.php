<?php

/**
 * This is the model class for table "{{referal_users}}".
 *
 * The followings are the available columns in table '{{referal_users}}':
 * @property integer $link_id
 * @property string $dt
 * @property string $action
 * @property integer $player_id
 */
class ReferalUsers extends CActiveRecord
{
	const ACTION_OPEN     = 'open';
	const ACTION_REGISTER = 'register';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{referal_users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('link_id, action', 'required'),
			array('link_id, player_id', 'numerical', 'integerOnly'=>true),
			array('action', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('link_id, dt, action, player_id', 'safe', 'on'=>'search'),
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
			'link_id' => 'Link',
			'dt' => 'Dt',
			'action' => 'Action',
			'player_id' => 'Player',
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

		$criteria->compare('link_id',$this->link_id);
		$criteria->compare('dt',$this->dt,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('player_id',$this->player_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ReferalUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function register($player_id){
		$linkId = Yii::app()->session->get(ReferalLinks::SESSION_SLUG, false);
		$model  = ReferalLinks::model()->findByPk($linkId);
		if ($model) {
			$log = new ReferalUsers();
			/** @var $log ReferalUsers */
			$log->player_id = $player_id;
			$log->action = ReferalUsers::ACTION_REGISTER;
			$log->link_id = $model->id;
			if (!$log->save()) {
				print_r($log->getErrors()); die();
			}
		}
		unset(Yii::app()->session[ReferalLinks::SESSION_SLUG]);
	}
}
