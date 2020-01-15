<?php

/**
 * This is the model class for table "npcdrop".
 *
 * The followings are the available columns in table 'npcdrop':
 * @property integer $npc
 * @property integer $item
 * @property integer $chance
 * @property integer $amin
 * @property integer $amax
 * @property integer $toprand
 * @property string $notify
 *
 * @property Equipment $equipment
 * @property Npc $mob
 */
class Npcdrop extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'npcdrop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('npc, item', 'required'),
			array('npc, item, chance, amin, amax, toprand', 'numerical', 'integerOnly'=>true),
			array('notify', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('npc, item, chance, amin, amax, toprand, notify', 'safe', 'on'=>'search'),
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
			'equipment' => [self::BELONGS_TO, 'Equipment', 'item'],
			'mob' => [self::BELONGS_TO, 'Npc', 'npc'],
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'npc' => 'ID NPC для которого дроп',
			'item' => 'Предмет',
			'chance' => 'Числитель вероятности выпадения',
			'toprand' => 'Знаменатель вероятности выпадения',
			'amin' => 'Минимальное количество выпадающих предметов',
			'amax' => 'Максимальное количество выпадающих предметов',
			'notify' => 'Нотификация (неиспользуемое поле)',
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

		$criteria->compare('npc',$this->npc);
		$criteria->compare('item',$this->item);
		$criteria->compare('chance',$this->chance);
		$criteria->compare('amin',$this->amin);
		$criteria->compare('amax',$this->amax);
		$criteria->compare('toprand',$this->toprand);
		$criteria->compare('notify',$this->notify,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Npcdrop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
