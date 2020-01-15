<?php

/**
 * This is the model class for table "npc".
 *
 * The followings are the available columns in table 'npc':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $pic
 * @property string $desc
 * @property integer $level
 * @property integer $gold
 * @property integer $strength
 * @property integer $agility
 * @property integer $platinum
 * @property string $gender
 * @property integer $hp
 * @property integer $max_hp
 * @property integer $defense
 * @property integer $ap
 * @property string $typeloc
 * @property integer $enabled
 *
 * @property NpcType $typeModel
 * @property Npcdrop[] $drop
 */
class Npc extends MLModel
{
    public $level_start;
    public $level_end;

	const TYPELOC_TOXIC_CAVES = 'T';
	const TYPELOC_WASTELAND = 'W';

	const GENDER_MALE = 'M';
	const GENDER_FEMALE = 'F';

	public $locations = [
		self::TYPELOC_TOXIC_CAVES => 'Пещеры',
		self::TYPELOC_WASTELAND => 'Пустошь',
	];

	public $genders = [
		self::GENDER_MALE   => 'Мужской',
		self::GENDER_FEMALE => 'Женский',
	];

	public $MLFields = ['name', 'desc'];

	public $image_tmp;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'npc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('desc', 'required'),
			array('type, pic, level, gold, strength, agility, platinum, hp, max_hp, defense, ap, enabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('gender, typeloc', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, pic, desc, level, gold, strength, agility, platinum, gender, hp, max_hp, defense, ap, typeloc, image_tmp, level_start, level_end', 'safe', 'on'=>'search'),
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
			'typeModel' => array(self::BELONGS_TO, 'NpcType', 'type'),
			'drop' => array(self::HAS_MANY, 'Npcdrop', ['npc' => 'id'])
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'type' => 'Тип',
			'pic' => 'Изображение',
			'desc' => 'Описание',
			'level' => 'Уровень',
			'gold' => 'Золота',
			'strength' => 'Сила',
			'agility' => 'Ловкость',
			'platinum' => 'Крышек',
			'gender' => 'Пол',
			'hp' => 'Жизни',
			'max_hp' => 'Максимально жизни',
			'defense' => 'Защита',
			'ap' => 'Очки способностей',
			'typeloc' => 'Локация',
			'image_tmp' => 'Изображение',
            'level_start' => 'Уровень (от)',
            'level_end' => 'Уровень (до)',
            'enabled' => 'Разрешен',
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
		$criteria->compare('type',$this->type);
//		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('gold',$this->gold);
		$criteria->compare('strength',$this->strength);
		$criteria->compare('agility',$this->agility);
		$criteria->compare('platinum',$this->platinum);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('hp',$this->hp);
		$criteria->compare('max_hp',$this->max_hp);
		$criteria->compare('defense',$this->defense);
		$criteria->compare('ap',$this->ap);
		$criteria->compare('typeloc',$this->typeloc,true);
		$criteria->compare('enabled',$this->enabled);

        if ($this->level_start && !is_null($this->level_start)) {
            $criteria->addCondition("level>='".$this->level_start."'");
        }

        if ($this->level_end && !is_null($this->level_end)) {
            $criteria->addCondition("level<='".$this->level_end."'");
        }

		parent::applyCriteria($criteria);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 25 //edit your number items per page here
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Npc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getImage(){
		$extension =  $this->get_npc_image_extension($this->pic);
		return "/avatars/npc_$this->pic.$extension";
	}

	public function get_npc_image_extension( $image_pic_id ) {
		if ( file_exists( basedir."/avatars/npc_{$image_pic_id}.gif" ) ) {
			return 'gif';
		} else {
			return 'png';
		}
	}

	public function getLocationName(){
		return isset($this->locations[$this->typeloc])?$this->locations[$this->typeloc]:$this->typeloc;
	}

	public function getEnabledDropdown()
	{
		$stats = array(
			0 => 'Запрещен',
			1 => 'Разрешен',
		);
		return CHtml::dropDownlist('enabled',$this->enabled,$stats, array(
			'class'     => 'enabled-state',
			'data-id'   => $this->id,
		));
	}

}
