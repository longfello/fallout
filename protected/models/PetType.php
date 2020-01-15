<?php

/**
 * This is the model class for table "pet_type".
 *
 * The followings are the available columns in table 'pet_type':
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property integer $level
 * @property string $gender
 * @property integer $price
 * @property integer $strength
 * @property integer $agility
 * @property integer $max_hp
 * @property integer $defense
 * @property integer $ap
 * @property integer $w_b
 * @property integer $w_k
 * @property integer $i_b
 * @property integer $i_k
 */
class PetType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pet_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('desc, w_b, w_k, i_b, i_k', 'required'),
			array('level, price, strength, agility, max_hp, defense, ap, w_b, w_k, i_b, i_k', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('gender', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, desc, level, gender, price, strength, agility, max_hp, defense, ap, w_b, w_k, i_b, i_k', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'desc' => 'Desc',
			'level' => 'Level',
			'gender' => 'Gender',
			'price' => 'Price',
			'strength' => 'Strength',
			'agility' => 'Agility',
			'max_hp' => 'Max Hp',
			'defense' => 'Defense',
			'ap' => 'Ap',
			'w_b' => 'W B',
			'w_k' => 'W K',
			'i_b' => 'I B',
			'i_k' => 'I K',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('strength',$this->strength);
		$criteria->compare('agility',$this->agility);
		$criteria->compare('max_hp',$this->max_hp);
		$criteria->compare('defense',$this->defense);
		$criteria->compare('ap',$this->ap);
		$criteria->compare('w_b',$this->w_b);
		$criteria->compare('w_k',$this->w_k);
		$criteria->compare('i_b',$this->i_b);
		$criteria->compare('i_k',$this->i_k);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PetType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
