<?php

/**
 * This is the model class for table "recipe_types".
 *
 * The followings are the available columns in table 'recipe_types':
 * @property integer $recipe_type_id
 * @property string $recipe_image
 * @property string $recipe_type_name
 * @property integer $add_crafting_chance
 * @property integer $currency
 */
class RecipeTypes extends MLModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recipe_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('recipe_image, recipe_type_name, add_crafting_chance', 'required'),
			array('add_crafting_chance, currency', 'numerical', 'integerOnly'=>true),
			array('recipe_image', 'length', 'max'=>127),
			array('recipe_type_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('recipe_type_id, recipe_image, recipe_type_name, add_crafting_chance, currency', 'safe', 'on'=>'search'),
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
			'recipe_type_id' => 'Recipe Type',
			'recipe_image' => 'Recipe Image',
			'recipe_type_name' => 'Recipe Type Name',
			'add_crafting_chance' => 'Add Crafting Chance',
			'currency' => 'Цена: 1 - в золоте, 2 - в крышках',
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

		$criteria->compare('recipe_type_id',$this->recipe_type_id);
		$criteria->compare('recipe_image',$this->recipe_image,true);
		$criteria->compare('recipe_type_name',$this->recipe_type_name,true);
		$criteria->compare('add_crafting_chance',$this->add_crafting_chance);
		$criteria->compare('currency',$this->currency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RecipeTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
