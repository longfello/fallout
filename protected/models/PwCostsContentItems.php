<?php

/**
 * This is the model class for table "{{pw_costs_content_items}}".
 *
 * The followings are the available columns in table '{{pw_costs_content_items}}':
 * @property integer $id
 * @property integer $content_id
 * @property string $name
 * @property string $type
 * @property string $count_rule
 * @property integer $count
 * @property string $rule
 * @property string $list
 * @property integer $chance
 *
 * @property PwCostsContent $content
 */
class PwCostsContentItems extends CActiveRecord
{
	const COUNT_RULE_EXACT = 'exact';
	const COUNT_RULE_MAX = 'max';

	const RULE_ANY = 'any';
	const RULE_IN = 'in';

	const TYPE_WEAPON    = 'weapon';
	const TYPE_ARMOR     = 'armor';
	const TYPE_EQUIPMENT = 'equipment';
	const TYPE_FOOD      = 'food';
	const TYPE_GOLD      = 'gold';
	const TYPE_PLATINUM  = 'platinum';
	const TYPE_ENERGY    = 'energy';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{pw_costs_content_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content_id, type, count_rule, count, rule', 'required'),
			array('id, content_id, count, chance', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>9),
			array('name', 'length', 'max'=>255),
			array('count_rule', 'length', 'max'=>5),
			array('list', 'length', 'max'=>65535),
			array('rule', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, content_id, type, count_rule, count, rule, list, chance', 'safe', 'on'=>'search'),
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
			'content' => array(self::HAS_ONE, 'PwCostsContent', array('id' => 'content_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content_id' => 'ID варианта',
			'name' => 'Название',
			'type' => 'Тип содержимого',
			'count_rule' => 'Правило для количества',
			'count' => 'Количество',
			'rule' => 'Правило содержимого',
			'list' => 'Список',
			'chance' => 'Шанс выпадания',
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
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('count_rule',$this->count_rule,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('rule',$this->rule,true);
		$criteria->compare('list',$this->list,true);
		$criteria->compare('chance',$this->chance);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PwCostsContentItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return Equipment[]
	 */
	public function getListItems(){
		$models = [];
		if ($this->list) {
			$ids = explode(',',$this->list);
			$ids = array_map(function($a){ return intval($a); }, $ids);
			$models = Equipment::model()->findAllByAttributes(array("id"=>$ids));
		}
		return $models;
	}
}
