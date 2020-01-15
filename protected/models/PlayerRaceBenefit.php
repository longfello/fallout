<?php

/**
 * This is the model class for table "player_race_benefit".
 *
 * The followings are the available columns in table 'player_race_benefit':
 * @property integer $id
 * @property integer $race_id
 * @property integer $benefit_id
 * @property integer $value
 *
 * @property Players $player
 * @property PlayerRaceBenefitList $benefit
 */
class PlayerRaceBenefit extends CActiveRecord
{
	const TYPE_YESNO    = 'YesNo';
	const TYPE_INTEGER  = 'Integer';

	const TOXIC_RESISTENT_ID = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'player_race_benefit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('race_id, benefit_id, value', 'required'),
			array('race_id, benefit_id, value', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, race_id, benefit_id, value', 'safe', 'on'=>'search'),
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
			'benefit'  => array(self::BELONGS_TO, 'PlayerRaceBenefitList', 'benefit_id'),
			'player'   => array(self::BELONGS_TO, 'Players', 'player_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'race_id' => 'Раса',
			'benefit_id' => 'Преимущество',
			'value' => 'Значение',
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
		$criteria->compare('race_id',$this->race_id);
		$criteria->compare('benefit_id',$this->benefit_id);
		$criteria->compare('value',$this->value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlayerRaceBenefit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
