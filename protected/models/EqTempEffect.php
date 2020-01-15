<?php

/**
 * This is the model class for table "eq_temp_effect".
 *
 * The followings are the available columns in table 'eq_temp_effect':
 * @property integer $id
 * @property integer $player_id
 * @property integer $eq_id
 * @property integer $end_time
 * @property integer $type
 */
class EqTempEffect extends MLModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'eq_temp_effect';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, eq_id, end_time, type', 'required'),
			array('player_id, eq_id, end_time, type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, player_id, eq_id, end_time, type', 'safe', 'on'=>'search'),
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
			'equipment' => array(self::HAS_ONE, 'Equipment', array('id'=>'eq_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'player_id' => 'ID персонажа из players',
			'eq_id' => 'ID предмета из equipment',
			'end_time' => 'Время окончания действия временного эффекта',
			'type' => '0 - положительный эффект, 1 - отрицательный эффект',
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
		$criteria->compare('eq_id',$this->eq_id);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EqTempEffect the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
