<?php

/**
 * This is the model class for table "equipment".
 *
 * The followings are the available columns in table 'equipment':
 * @property integer $id
 * @property integer $owner
 * @property string $name
 * @property string $status
 * @property string $type
 * @property integer $cost
 * @property string $mtype
 * @property integer $minlev
 * @property string $opis
 * @property integer $clan
 * @property integer $shoplvl
 * @property string $eprot
 * @property string $uname
 * @property integer $weight
 * @property string $product
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
 * @property integer $add_hp
 * @property integer $add_energy
 * @property integer $add_pohod
 * @property integer $time_effect
 * @property integer $durability
 * @property string $class
 * @property integer $no_weapon
 * @property integer $toxic
 * @property integer $is_caves_drop
 * @property string $className
 * @property string $params
 */
class Equipment extends MLModel
{
	public $MLFields = ['name', 'opis'];
	const TYPE_ARMOR           = 'A';
	const TYPE_BOX             = 'B';
	const TYPE_ENERGETICS      = 'D';
	const TYPE_FOOD            = 'F';
	const TYPE_EVENT_ITEM      = 'G';
	const TYPE_ANIMAL_PICE     = 'M';
	const TYPE_EQUIPMENT_TOXIC = 'T';
	const TYPE_EQUIPMENT       = 'U';
	const TYPE_WEAPON          = 'W';
	const TYPE_ANIMAL_PICE_EAT = 'X';

	const IMAGE_ORIGINAL_FULL    = 'male_full.png';
	const IMAGE_ORIGINAL         = 'male.png';
	const IMAGE_FEMALE           = 'female.png';
	const IMAGE_SMALL            = 'small.png';
	const IMAGE_HAND             = 'hand_male.png';
	const IMAGE_HAND_FEMALE      = 'hand_female.png';

	public $imagesAvailable = [
		self::IMAGE_ORIGINAL         => 'Обычное изображение / мужской вариант',
		self::IMAGE_ORIGINAL_FULL    => 'Увеличенное изображение',
		self::IMAGE_FEMALE           => 'Обычное изображение / женский вариант',
		self::IMAGE_SMALL            => 'Инвентарный вариант',
		self::IMAGE_HAND             => 'Слой с руками мужского варианта',
		self::IMAGE_HAND_FEMALE      => 'Слой с руками женского варианта',
	];

	public $YesNo = [
		0 => 'Нет',
		1 => 'Да',
	];

	public $locationAvailable = [
		'' => 'Без ограничения',
		'caves' => 'Пещеры'
	];

	public $classAvaliable = [
		'S' => 'Самодельный',
		'F' => 'Фабричный',
		'M' => 'Мастерский'
	];

	public $mtypeAvaliable = [
		'G' => 'Золото',
		'P' => 'Крышки',
		'W' => 'Вода',
	];

	public $statusAvailable = [
		'S' => 'S',
		'B' => 'B',
		'U' => 'U',
		'C' => 'C',
	];
	public $typesAvailable = [
		'A' => 'броня',
		'B' => 'ящики',
		'D' => 'энергетики',
		'F' => 'еда',
		'G' => 'ивентовые предметы',
		'M' => 'квестовые, ивентовые, фетишные предметы',
		'T' => 'оборудование для токсических пещер',
		'U' => 'оборудование',
		'W' => 'оружие',
		'X' => 'куски животных',
	];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'equipment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, opis, class, toxic, is_caves_drop', 'required'),
			array('owner, cost, minlev, clan, shoplvl, weight, min_strength, min_agility, min_defense, min_max_energy, min_max_hp, add_strength, add_agility, add_defense, add_max_energy, add_max_hp, add_hp, add_energy, add_pohod, time_effect, durability, no_weapon, toxic, is_caves_drop', 'numerical', 'integerOnly'=>true),
			array('uname', 'length', 'max'=>32),
			array('name', 'length', 'max'=>64),
			array('status, type, mtype, eprot, class', 'length', 'max'=>1),
			array('product, className, params', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner, name, status, type, cost, mtype, minlev, opis, clan, shoplvl, eprot, uname, weight, product, min_strength, min_agility, min_defense, min_max_energy, min_max_hp, add_strength, add_agility, add_defense, add_max_energy, add_max_hp, add_hp, add_energy, add_pohod, time_effect, durability, class, no_weapon, toxic, is_caves_drop', 'safe', 'on'=>'search'),
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
            'clans' => array(self::BELONGS_TO, 'RClans', 'id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner' => 'ID владельца',
			'name' => 'Наименование',
			'status' => 'Статус',
			'type' => 'Тип предмета',
			'cost' => 'Стоимость',
			'mtype' => 'Валюта стоимости',
			'minlev' => 'Минимальный уровень',
			'opis' => 'Описание',
			'clan' => 'Клан',
			'shoplvl' => 'Уровень магазина',
			'eprot' => 'Защита от радиации (для брони)',
			'uname' => 'Псевдоним (для использования в коде)',
			'weight' => 'Вес',
			'product' => 'список предметов которые при вскрытии выпадут (ящики)',
			'min_strength' => 'Минимальная сила',
			'min_agility' => 'Минимальная ловкость',
			'min_defense' => 'Минимальная защита',
			'min_max_energy' => 'Минимальная энергия',
			'min_max_hp' => 'Минимальная жизнь',
			'add_strength' => 'Добавляет силы',
			'add_agility' => 'Добавляет ловкости',
			'add_defense' => 'Добавляет защиты',
			'add_max_energy' => 'Добавляет максимальной энергии',
			'add_max_hp' => 'Добавляет максимальной эизни',
			'add_hp' => 'Добавляет жизни',
			'add_energy' => 'Добавляет энергии',
			'add_pohod' => 'Добавляет походных очков',
			'time_effect' => 'Продолжительность действия эффекта',
			'durability' => 'Продолжительность жизни предмета',
			'class' => 'Класс предмета',
			'no_weapon' => 'Для брони. Позволять носить оружие?',
			'toxic' => 'Предмет продается в токсических пещерах в магазине',
			'is_caves_drop' => 'Предмет-дроп с токсических пещер',
			'post_time_effect' => 'Продолжительность пост-эффекта',
			'post_strength' => 'Пост-эффект силы',
			'post_agility' => 'Пост-эффект ловкости',
			'post_defense' => 'Пост-эффект защиты',
			'post_max_energy' => 'Пост-эффект максимальной энергии',
			'post_max_hp' => 'Пост-эффект максимального здоровья',
			'post_hp' => 'Пост-эффект здоровья',
			'post_energy' => 'Пост-эффект энергии',
			'post_pohod' => 'Пост-эффект походных очков',
			'location_use' => 'Допустимая локация для использования',
			'className' => 'Класс PHP обработчика (для обычных предметов оставить пустым)',
			'params' => 'Параметры для PHP обработчика (для обычных предметов оставить пустым)',
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
		$criteria->compare('owner',$this->owner);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('mtype',$this->mtype,true);
		$criteria->compare('minlev',$this->minlev);
		$criteria->compare('clan',$this->clan);
		$criteria->compare('shoplvl',$this->shoplvl);
		$criteria->compare('eprot',$this->eprot,true);
		$criteria->compare('uname',$this->uname,true);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('product',$this->product);
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
		$criteria->compare('add_hp',$this->add_hp);
		$criteria->compare('add_energy',$this->add_energy);
		$criteria->compare('add_pohod',$this->add_pohod);
		$criteria->compare('time_effect',$this->time_effect);
		$criteria->compare('durability',$this->durability);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('no_weapon',$this->no_weapon);
		$criteria->compare('toxic',$this->toxic);
		$criteria->compare('is_caves_drop',$this->is_caves_drop);

		parent::applyCriteria($criteria);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Equipment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return BoxPrototype
	 */
	public static function getHandlerByClass(Equipment $equipment){
		$param = CJSON::decode($equipment->params);
		$className = $equipment->className;
		return new $className($equipment, $param);
	}


	public function getTypeName(){
		return isset($this->typesAvailable[$this->type])?$this->typesAvailable[$this->type]:'?';
	}
	public function getMTypeName(){
		switch($this->mtype){
			case 'G':
				return 'Золото';
				break;
			case 'P':
				return 'Крышки';
				break;
			case 'W':
				return 'Вода';
				break;
			default:
				return '?';
		}
	}

	public function getPicture($type = false, $placeDefault = false){
		$slug = 'eq-pic-'.$this->id.'_'.$type.'_'.md5(serialize($placeDefault));
		$image = Yii::app()->cache->get($slug);
		if (!$image) {
			if (!isset($this->imagesAvailable[$type])){
				$type = self::IMAGE_ORIGINAL;
			}

			if (is_array($placeDefault)) {
				foreach ($placeDefault as $one){
					$type = $this->correctType($type, $one);
				}
			} else {
				$type = $this->correctType($type, $placeDefault);
			}
			$type = $this->correctType($type, self::IMAGE_ORIGINAL);

			if (!$this->imageExists($type)) {
				if ($placeDefault !== false){
					if ($placeDefault === true) return '/images/no_image.png';
					return $this->getPicture(Equipment::IMAGE_ORIGINAL);
				}
				return false;
			}
			$image = $this->getImagePath($type);
			Yii::app()->cache->set($slug, $image);
		}
		return $image;
	}

	public function correctType($type, $fallback = false){
		if (!$this->imageExists($type) && $fallback){
			$type = $fallback;
		}
		return $type;
	}

	public function imageExists($type){
		$path = $this->getImagePath($type);
		return file_exists(basedir.$path);
	}

	public function getImagePath($type){
		$subdirs = [];
		$subdirs[] = substr($this->id, 0, 1);
		$subdirs[] = substr($this->id, 0, 3);
		$subdirs = implode('/', $subdirs);
		$path = '/img/equipment/'.$subdirs.'/'.$this->id.'/';
		if (!is_dir(basedir.$path)) {
			mkdir(basedir.$path, 0777, true);
		}
		return $path.$type;
	}

	public function getPicSubdir(){
		/* TODO: Удалить данную устаревшую функцию */
		switch($this->type) {
			case "F":
				$dir = "food";
				break;
			case "D":
				$dir = "food";
				break;
			case "W":
				$dir = "wpn";
				break;
			case "M":
				$dir = "misc";
				break;
			case "G":
				$dir = "gifts";
				break;
			case "U":
				$dir = "misc";
				break;
			case "A":
				$dir = "rmr";
				break;
			default:
				$dir = "misc";
				break;
		}
		return $dir;
	}
}
