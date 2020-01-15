<?php

/**
 * This is the model class for table "user_recipes".
 *
 * The followings are the available columns in table 'user_recipes':
 * @property integer $user_recipe_id
 * @property integer $user_id
 * @property integer $recipe_id
 * @property integer $using_cnt
 * @property string $creationdate
 */
class UserRecipes extends MLModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_recipes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, recipe_id, using_cnt, creationdate', 'required'),
			array('user_id, recipe_id, using_cnt', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_recipe_id, user_id, recipe_id, using_cnt, creationdate', 'safe', 'on'=>'search'),
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
			'user_recipe_id' => 'User Recipe',
			'user_id' => 'User',
			'recipe_id' => 'Recipe',
			'using_cnt' => 'Using Cnt',
			'creationdate' => 'Creationdate',
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

		$criteria->compare('user_recipe_id',$this->user_recipe_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('recipe_id',$this->recipe_id);
		$criteria->compare('using_cnt',$this->using_cnt);
		$criteria->compare('creationdate',$this->creationdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserRecipes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
