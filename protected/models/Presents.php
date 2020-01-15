<?php

/**
 * This is the model class for table "presents".
 *
 * The followings are the available columns in table 'presents':
 * @property integer $id
 * @property string $pic
 * @property string $name
 * @property integer $price
 * @property integer $hidden
 * @property integer $clan
 * @property integer $owner
 *
 * @property Clans $clanModel
 * @property Players $playerModel
 */
class Presents extends MLModel
{
	const IMAGE_DEFAULT = 'default';
	const IMAGE_BIG     = 'full';
	const IMAGE_SMALL   = 'small';

	const IMAGE_PATH  = '/images/podarki/';

	public $MLFields = array('name');
	public $image;
	public $imageBig;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'presents';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('price, hidden, clan, owner', 'numerical', 'integerOnly'=>true),
			array('pic', 'length', 'max'=>32),
			array('name', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pic, name, price, hidden, clan, owner', 'safe', 'on'=>'search'),
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
			'clanModel' => array(self::BELONGS_TO, 'Clans', 'clan'),
			'playerModel' => array(self::BELONGS_TO, 'Players', 'owner')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pic' => 'Картинка',
			'name' => 'Название',
			'price' => 'Стоимость',
			'hidden' => 'Скрытый',
			'clan' => 'Клан',
			'owner' => 'Игрок',
			'image' => 'Изображение',
			'imageBig' => 'Большое изображение',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('hidden',$this->hidden);
		if ($this->clan) {
			if (is_numeric($this->clan)) {
				$criteria->compare('clan',$this->clan);
			} else {
				$criteria->join .= " LEFT JOIN clans ON clans.id = t.clan ";
				$criteria->compare('clans.name',$this->clan, true);
			}
		}
		if ($this->owner) {
			if (is_numeric($this->owner)) {
				$criteria->compare('owner',$this->owner);
			} else {
				$criteria->join .= " LEFT JOIN players ON players.id = t.owner ";
				$criteria->compare('players.user', $this->owner, true);
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 25
			),
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Presents the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeDelete() {
		if (parent::beforeDelete()){
			PlayerPresents::model()->deleteAllByAttributes(['present' => $this->id]);
			return true;
		}
		return false;
	}

	public function getImageUrl($size = self::IMAGE_SMALL){
		$default = $this->getPath(self::IMAGE_DEFAULT);
		$full    = $this->getPath(self::IMAGE_BIG);
		$small   = $this->getPath(self::IMAGE_SMALL);
		$file    = $small;
		switch($size){
			case self::IMAGE_SMALL:
				if ( !file_exists( basedir . $small ) ) {
					$file = $default;
				}
				break;
			case self::IMAGE_BIG:
				$file = $full;
				if ( ! file_exists( basedir . $full ) ) {
					$file = $small;
					if ( ! file_exists( basedir . $file ) ) {
						$file = $default;
					}
				}
				break;
		}
		return $file;
	}

	public function generatePic(){
		if (!$this->pic){
			if ($this->id){
				$this->pic = $this->id.'-'.uniqid();
			} else {
				$this->pic = uniqid().'-'.uniqid();
			}
		}
	}

	public function getPath($size = self::IMAGE_SMALL){
		$this->generatePic();
		$path = self::IMAGE_PATH.'default.png';
		switch($size) {
			case self::IMAGE_SMALL:
				$path = self::IMAGE_PATH. $this->pic . '.png';
				break;
			case self::IMAGE_BIG:
				$path = self::IMAGE_PATH. 'full/' . $this->pic . '.png';
				break;
			default:
		}
		return $path;
	}

	public function getColumnGiftedCount() {

		$sql = "SELECT
					COUNT(up.id)
				FROM
					upresents up
				WHERE up.`present`='{$this->id}'";
		$presents_count = intval(Yii::app()->db->createCommand($sql)->queryScalar());

		return "<a href='".Yii::app()->createUrl("admin/playerPresents/index", array("present_id"=>$this->id))."'>".$presents_count."</a>";
	}
}
