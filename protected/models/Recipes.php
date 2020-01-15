<?php

/**
 * This is the model class for table "recipes".
 *
 * The followings are the available columns in table 'recipes':
 * @property integer $recipe_id
 * @property integer $recipe_type_id
 * @property string $recipe_name
 * @property string $recipe_description
 * @property integer $crafting_id
 * @property integer $cost
 * @property integer $using_cnt
 * @property integer $location
 */
class Recipes extends MLModel
{
	public $MLFields = ['recipe_name', 'recipe_description'];

    public $availableLocations = [
        1 => 'Город',
        2 => 'Пещеры',
    ];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recipes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('recipe_type_id, recipe_name, recipe_description, crafting_id', 'required'),
			array('recipe_type_id, crafting_id, cost, using_cnt, location', 'numerical', 'integerOnly'=>true),
			array('recipe_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('recipe_id, recipe_type_id, recipe_name, recipe_description, crafting_id, cost, using_cnt, location', 'safe', 'on'=>'search'),
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
            'recipeTypeModel' => array(self::BELONGS_TO, 'RecipeTypes', 'recipe_type_id'),
            'craftingModel' => array(self::HAS_ONE, 'Crafting', array('id'=>'crafting_id')),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'recipe_id' => 'Id рецепта',
			'recipe_type_id' => 'Тип рецепта',
			'recipe_name' => 'Название рецепта',
			'recipe_description' => 'Описание рецепта',
			'crafting_id' => 'Id крафтинга',
			'cost' => 'Стоимость рецепта',
			'using_cnt' => 'Количество использований',
			'location' => 'Где продается рецепт',
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

		$criteria->compare('recipe_id',$this->recipe_id);
		$criteria->compare('recipe_type_id',$this->recipe_type_id);
		$criteria->compare('recipe_name',$this->recipe_name,true);
		$criteria->compare('recipe_description',$this->recipe_description,true);
		$criteria->compare('crafting_id',$this->crafting_id);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('using_cnt',$this->using_cnt);
		$criteria->compare('location',$this->location);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'Pagination' => array (
                'PageSize' => 50 //edit your number items per page here
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Recipes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
