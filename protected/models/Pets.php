<?php

/**
 * This is the model class for table "pets".
 *
 * The followings are the available columns in table 'pets':
 * @property integer $owner
 * @property string $name
 * @property integer $type
 * @property integer $level
 * @property integer $strength
 * @property integer $agility
 * @property integer $defense
 * @property integer $max_hp
 * @property integer $exp
 * @property integer $hp
 * @property integer $ap
 * @property string $trial
 * @property integer $implgold
 * @property integer $implplat
 *
 * @property PetType $petType
 */
class Pets extends CActiveRecord
{
  public $isLevelUp = false;
  public $ap_gain   = 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner', 'required'),
			array('owner, type, level, strength, agility, defense, max_hp, exp, hp, ap, implgold, implplat', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('trial', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('owner, name, type, level, strength, agility, defense, max_hp, exp, hp, ap, trial, implgold, implplat', 'safe', 'on'=>'search'),
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
			'petType' => array(self::HAS_ONE, 'PetType', array('id' => 'type')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'owner' => 'Owner',
			'name' => 'Name',
			'type' => 'Type',
			'level' => 'Level',
			'strength' => 'Strength',
			'agility' => 'Agility',
			'defense' => 'Defense',
			'max_hp' => 'Max Hp',
			'exp' => 'Exp',
			'hp' => 'Hp',
			'ap' => 'Ap',
			'trial' => 'Trial',
			'implgold' => 'Implgold',
			'implplat' => 'Implplat',
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

		$criteria->compare('owner',$this->owner);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('level',$this->level);
		$criteria->compare('strength',$this->strength);
		$criteria->compare('agility',$this->agility);
		$criteria->compare('defense',$this->defense);
		$criteria->compare('max_hp',$this->max_hp);
		$criteria->compare('exp',$this->exp);
		$criteria->compare('hp',$this->hp);
		$criteria->compare('ap',$this->ap);
		$criteria->compare('trial',$this->trial,true);
		$criteria->compare('implgold',$this->implgold);
		$criteria->compare('implplat',$this->implplat);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function alive(){
    $this->dbCriteria->addCondition("hp > 0");
    return $this;
  }

  public function addExperience($howMany){
    $this->exp = ($this->exp + $howMany);
    /* END MAXV */
    if (($this->exp >= PetExp::getExperienceForNextLevel($this->level)) && ($this->hp > 0)){
      /** @var $petType PetType */
      $petType = PetType::model()->findByPk($this->type);

      $this->level++;
      $this->isLevelUp = true;
      $this->ap_gain   = $petType->ap;
      $this->ap       += $petType->ap;
      $this->exp       = 0;
    }
    $this->save();
  }
}
