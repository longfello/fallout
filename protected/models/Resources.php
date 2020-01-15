<?php

/**
 * This is the model class for table "resources".
 *
 * The followings are the available columns in table 'resources':
 * @property integer $id
 * @property integer $gold
 * @property integer $water
 * @property integer $platinum
 * @property string $date
 * @property integer $bank
 * @property integer $gold_clans
 * @property integer $platinum_clans
 */
class Resources extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'resources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gold, water, platinum, bank, gold_clans, platinum_clans', 'numerical', 'integerOnly'=>true),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, gold, water, platinum, date, bank, gold_clans, platinum_clans', 'safe', 'on'=>'search'),
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
			'gold' => 'Gold',
			'water' => 'Water',
			'platinum' => 'Platinum',
			'date' => 'Date',
			'bank' => 'Bank',
			'gold_clans' => 'Gold Clans',
			'platinum_clans' => 'Platinum Clans',
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
		$criteria->compare('gold',$this->gold);
		$criteria->compare('water',$this->water);
		$criteria->compare('platinum',$this->platinum);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('bank',$this->bank);
		$criteria->compare('gold_clans',$this->gold_clans);
		$criteria->compare('platinum_clans',$this->platinum_clans);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Resources the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
