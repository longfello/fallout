<?php

/**
 * This is the model class for table "player_race_appearance_list".
 *
 * The followings are the available columns in table 'player_race_appearance_list':
 * @property integer $id
 * @property integer $race_id
 * @property string $gender
 * @property string $original_filename
 * @property integer $appearance_layout_id
 * @property integer $default_layout
 *
 * @property AppearanceLayout $layout
 */
class PlayerRaceAppearanceList extends CActiveRecord
{
	public $image;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'player_race_appearance_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('race_id, appearance_layout_id', 'required'),
			array('race_id, appearance_layout_id, default_layout', 'numerical', 'integerOnly'=>true),
			array('gender', 'length', 'max'=>6),
			array('original_filename', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, race_id, gender, appearance_layout_id, original_filename, default_layout', 'safe', 'on'=>'search'),
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
			'layout' => array(self::BELONGS_TO, 'AppearanceLayout', 'appearance_layout_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'race_id' => 'Раса',
			'gender' => 'Пол',
			'appearance_layout_id' => 'Слой',
			'image' => 'Изображение',
			'default_layout' => 'По-умолчанию',
			'original_filename' => 'Имя файла (для обратной совместимости - после запуска удалить можно)', /* TODO: удалить поле после конвертации. */
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
		$criteria->compare('race_id',$this->race_id);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('appearance_layout_id',$this->appearance_layout_id);
		$criteria->compare('default_layout',$this->default_layout);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlayerRaceAppearanceList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getPicture($layout='layout', $placeDefault = false){
		$slug = 'ap-pic-'.$this->id.'_'.md5(serialize($placeDefault));
		$image = false; // Yii::app()->cache->get($slug);
		if (!$image) {
			if (!$this->imageExists($layout)) {
				return ($placeDefault)?'/images/no_image.png':false;
			}
			$image = $this->getImagePath($layout);
			Yii::app()->cache->set($slug, $image);
		}
		return $image;
	}

	public function imageExists($layout){
		$path = $this->getImagePath($layout);
		return file_exists(basedir.$path);
	}

	public function getImagePath($layout){
		$subdirs = [];
		$subdirs[] = substr($this->id, 0, 1);
		$subdirs[] = substr($this->id, 0, 3);
		$subdirs = implode('/', $subdirs);
		$path = '/img/appearance/'.$subdirs.'/'.$this->id.'/';
		if (!is_dir(basedir.$path)) {
			mkdir(basedir.$path, 0777, true);
		}
		return $path.$layout.'.png';
	}
}
