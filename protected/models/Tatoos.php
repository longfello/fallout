<?php

/**
 * This is the model class for table "tatoos".
 *
 * The followings are the available columns in table 'tatoos':
 * @property integer $id
 * @property string $picname
 * @property integer $owner
 * @property string $name
 * @property integer $cost
 * @property string $mtype
 * @property integer $minlev
 * @property string $opis
 * @property integer $clan
 * @property integer $min_strength
 * @property integer $min_agility
 * @property integer $min_defense
 * @property integer $min_max_energy
 * @property integer $min_max_hp
 * @property integer $add_strength
 * @property integer $add_agility
 * @property integer $add_defense
 * @property integer $add_max_energy
 * @property integer $add_max_hp
 * @property integer $critmod
 * @property integer $regen_count
 * @property integer $regen_decrease
 * @property integer $napad
 * @property string $count
 * @property integer $days
 */
class Tatoos extends MLModel
{
	public $MLFields = ['name', 'opis'];

	public $regen_count = 0;
	public $regen_decrease = 0;

	public $mtypeAvaliable = [
		'gold' => 'Золото',
		'platinum' => 'Крышки'
	];

	public $change_player_attributes = [
		'max_hp',
		'max_energy',
		'strength',
		'agility',
		'defense'
	];

	public $min_player_attributes = [
		'max_hp',
		'max_energy',
		'strength',
		'agility',
		'defense'
	];

	public $image_tmp;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tatoos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, mtype, opis, regen_count, regen_decrease, count', 'required'),
			array('owner, cost, minlev, clan, min_strength, min_agility, min_defense, min_max_energy, min_max_hp, add_strength, add_agility, add_defense, add_max_energy, add_max_hp, critmod, regen_count, regen_decrease, napad, days', 'numerical', 'integerOnly'=>true),
			array('picname', 'length', 'max'=>20),
			array('image_tmp', 'file', 'types'=>'png', 'safe' => false, 'allowEmpty' => true),
			array('name', 'length', 'max'=>64),
			array('mtype', 'length', 'max'=>8),
			array('count', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, picname, owner, name, cost, mtype, minlev, opis, clan, min_strength, min_agility, min_defense, min_max_energy, min_max_hp, add_strength, add_agility, add_defense, add_max_energy, add_max_hp, critmod, regen_count, regen_decrease, napad, count, days', 'safe', 'on'=>'search'),
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
			'picname' => 'Картинка',
			'image_tmp' => 'Картинка',
			'owner' => 'ID владельца',
			'name' => 'Название',
			'cost' => 'Стоимость',
			'mtype' => 'Валюта стоимости',
			'minlev' => 'Мин.уровень',
			'opis' => 'Описание',
			'clan' => 'ID клана',
			'min_strength' => 'Минимальная сила',
			'min_agility' => 'Минимальная ловкость',
			'min_defense' => 'Минимальная защита',
			'min_max_energy' => 'Минимальная энергия',
			'min_max_hp' => 'Минимальная жизнь',
			'add_strength' => 'Добавляет силы',
			'add_agility' => 'Добавляет ловкости',
			'add_defense' => 'Добавляет защиты',
			'add_max_energy' => 'Добавляет максимальной энергии',
			'add_max_hp' => 'Добавляет максимальной жизни',
			'critmod' => 'Critmod',
			'regen_count' => 'Количество регенераций',
			'regen_decrease' => 'Уменьшение регенерации',
			'napad' => 'Очки нападений',
			'count' => 'Количество набитий',
			'days' => 'Кол-во дней действия тату',
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
		$criteria->compare('picname',$this->picname,true);
		$criteria->compare('owner',$this->owner);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('mtype',$this->mtype,true);
		$criteria->compare('minlev',$this->minlev);
		$criteria->compare('clan',$this->clan);
		$criteria->compare('min_strength',$this->min_strength);
		$criteria->compare('min_agility',$this->min_agility);
		$criteria->compare('min_defense',$this->min_defense);
		$criteria->compare('min_max_energy',$this->min_max_energy);
		$criteria->compare('min_max_hp',$this->min_max_hp);
		$criteria->compare('add_strength',$this->add_strength);
		$criteria->compare('add_agility',$this->add_agility);
		$criteria->compare('add_defense',$this->add_defense);
		$criteria->compare('add_max_energy',$this->add_max_energy);
		$criteria->compare('add_max_hp',$this->add_max_hp);
		$criteria->compare('critmod',$this->critmod);
		$criteria->compare('regen_count',$this->regen_count);
		$criteria->compare('regen_decrease',$this->regen_decrease);
		$criteria->compare('napad',$this->napad);
		$criteria->compare('count',$this->count,true);
		$criteria->compare('days',$this->days);

		parent::applyCriteria($criteria);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tatoos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getMTypeName(){
		switch($this->mtype){
			case 'gold':
				return 'Золото';
				break;
			case 'platinum':
				return 'Крышки';
				break;
			default:
				return '?';
		}
	}

	public function beforeSave() {
		if (!$this->isNewRecord) {

			$tatoo_old = self::findByPk($this->id);

			$ptatoos = Utatoos::model()->findAllByAttributes(array('tatoo' => $this->id), 'timeout=0');
			foreach ($ptatoos as $pt) {
				$player_model = Players::model()->findByPk($pt->owner);

				foreach ($this->change_player_attributes as $attr) {
					$tattr = 'add_' . $attr;
					$tatoo_attr_val_old = (int)$tatoo_old->getAttribute($tattr);
					$tatoo_attr_val_new = (int)$this->getAttribute($tattr);
					$player_attr_val = (int)$player_model->getAttribute($attr);
					if ($tatoo_attr_val_old > 0 || $tatoo_attr_val_new > 0) {
						$player_model->setAttribute($attr, $player_attr_val - $tatoo_attr_val_old + $tatoo_attr_val_new);
					}
				}

				$player_model->save(false);
			}
		}
		return parent::beforeSave(); // TODO: Change the autogenerated stub
	}

	public function beforeDelete() {
		//Utatoos::model()->deleteAllByAttributes(array('tatoo' => $this->id));
		$ptatoos = Utatoos::model()->findAllByAttributes(array('tatoo' => $this->id));
		foreach ($ptatoos as $pt) {
			$pt->delete();
		}
		return parent::beforeDelete(); // TODO: Change the autogenerated stub
	}
}
