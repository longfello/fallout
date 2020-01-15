<?php

/**
 * This is the model class for table "crafting_items".
 *
 * The followings are the available columns in table 'crafting_items':
 * @property integer $crafting
 * @property integer $item
 * @property integer $count
 * @property string $disappear
 *
 * @property Equipment $equipment
 */
class CraftingItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'crafting_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('crafting, item', 'required'),
			array('crafting, item, count', 'numerical', 'integerOnly'=>true),
			array('disappear', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('crafting, item, count, disappear', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'crafting' => 'ID крафтинга из crafting',
			'item' => 'Предмет, используемый для крафтинга',
			'count' => 'Количество предмета, необходимое для  рецепта',
			'disappear' => 'Пропадает ли предмет из инвентаря при крафтинге',
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

		$criteria->compare('crafting',$this->crafting);
		$criteria->compare('item',$this->item);
		$criteria->compare('count',$this->count);
		$criteria->compare('disappear',$this->disappear,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CraftingItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
