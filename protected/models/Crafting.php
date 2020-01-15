<?php

/**
 * This is the model class for table "crafting".
 *
 * The followings are the available columns in table 'crafting':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $minlev
 * @property integer $maxlev
 * @property integer $minpro
 * @property integer $maxpro
 * @property integer $energy
 * @property integer $chance
 * @property integer $chancepp
 * @property integer $toprand
 * @property integer $success_item
 * @property integer $success_item_count
 * @property string $fail_text
 * @property integer $fail_item
 * @property integer $fail_item_count
 * @property integer $max_exp_prolvl
 *
 * @property CraftingItems[] $items
 */
class Crafting extends MLModel
{
	const PROFESSION_FLUNKEY = 'F';
	const PROFESSION_DRUGGIST = 'D';
	const PROFESSION_WEAPONIST = 'W';

	public $MLFields = ['fail_text', 'name'];

	public $availableProfessions = [
		self::PROFESSION_FLUNKEY => 'Повар',
		self::PROFESSION_DRUGGIST => 'Фармацевт',
		self::PROFESSION_WEAPONIST => 'Кузнец',
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'crafting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, success_item, fail_text, max_exp_prolvl', 'required'),
			array('minlev, maxlev, minpro, maxpro, energy, chance, chancepp, toprand, success_item, success_item_count, fail_item, fail_item_count, max_exp_prolvl', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('type', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, minlev, maxlev, minpro, maxpro, energy, chance, chancepp, toprand, success_item, success_item_count, fail_text, fail_item, fail_item_count, max_exp_prolvl', 'safe', 'on'=>'search'),
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
			'items' => [self::HAS_MANY, 'CraftingItems', 'crafting']
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Заголовок/Описание',
			'type' => 'Тип профессии', // . F-повар, D-фармацевт, W-кузнец
			'minlev' => 'Минимальный уровень игрока, который может использовать этот рецепт',
			'maxlev' => 'Максимальный уровень игрока, который может использовать этот рецепт',
			'minpro' => 'Минимальный уровень профессии для использования рецепта',
			'maxpro' => 'Максимальный уровень профессии для использования рецепта',
			'energy' => 'Затрата энергии для крафтинга',
			'chance' => 'Шанс успеха',
			'chancepp' => 'Дополнительный шанс за каждый уровень профессии выше minpro',
			'toprand' => 'Единица измерения шанса',
			'success_item' => 'Получаемый предмет в случае успешного крафта',
			'success_item_count' => 'Количество предметов',
			'fail_text' => 'Текст, отображаемый при неуспешном крафте',
			'fail_item' => 'Предмет, получаемый при неуспешном крафте',
			'fail_item_count' => 'Количество предметов при неудачном крафте',
			'max_exp_prolvl' => 'Максимальный уровень профессии, до которой успешный крафт будет прибавлять очки опыта',
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
//		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('minlev',$this->minlev);
		$criteria->compare('maxlev',$this->maxlev);
		$criteria->compare('minpro',$this->minpro);
		$criteria->compare('maxpro',$this->maxpro);
		$criteria->compare('energy',$this->energy);
		$criteria->compare('chance',$this->chance);
		$criteria->compare('chancepp',$this->chancepp);
		$criteria->compare('toprand',$this->toprand);
		$criteria->compare('success_item',$this->success_item);
		$criteria->compare('success_item_count',$this->success_item_count);
//		$criteria->compare('fail_text',$this->fail_text,true);
		$criteria->compare('fail_item',$this->fail_item);
		$criteria->compare('fail_item_count',$this->fail_item_count);
		$criteria->compare('max_exp_prolvl',$this->max_exp_prolvl);

		parent::applyCriteria($criteria);

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
	 * @return Crafting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
