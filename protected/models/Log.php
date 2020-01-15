<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $dt
 * @property integer $owner
 * @property string $log
 * @property string $unread
 * @property integer $CategoryId
 * @property string $data
 */
class Log extends CActiveRecord
{
	const CATEGORY_PVP = 1;
	const CATEGORY_MARKET = 2;
	const CATEGORY_HOUSE = 3;
	const CATEGORY_ETC = 4;
	const CATEGORY_WASTELAND = 5;
	const CATEGORY_NONE = NULL;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('log', 'required'),
			array('owner, CategoryId', 'numerical', 'integerOnly'=>true),
			array('unread', 'length', 'max'=>1),
			array('data', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dt, owner, log, unread, CategoryId, data', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'LogCategories', 'CategoryId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dt' => 'Дата, время',
			'owner' => 'Игрок',
			'log' => 'Текст',
			'unread' => 'Почтено',
			'CategoryId' => 'Категория',
			'data' => 'Данные рендеринга',
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
		$criteria->compare('dt',$this->dt,true);
		$criteria->compare('owner',$this->owner);
		$criteria->compare('log',$this->log,true);
		$criteria->compare('unread',$this->unread,true);
		$criteria->compare('CategoryId',$this->CategoryId);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 50
              ),
			'sort'=>array(
                'defaultOrder'=>'dt DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
