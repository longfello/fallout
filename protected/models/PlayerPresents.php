<?php

/**
 * This is the model class for table "upresents".
 *
 * The followings are the available columns in table 'upresents':
 * @property integer $id
 * @property integer $owner
 * @property integer $present
 * @property integer $giver
 * @property string $message
 * @property string $date
 *
 * @property Players $ownerModel
 * @property Players $giverModel
 * @property Presents $presentModel
 */
class PlayerPresents extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'upresents';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('present, date', 'required'),
			array('owner, present, giver', 'numerical', 'integerOnly'=>true),
			array('message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner, present, giver, message, date', 'safe', 'on'=>'search'),
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
			'ownerModel' => array(self::BELONGS_TO, 'Players', 'owner'),
			'giverModel' => array(self::BELONGS_TO, 'Players', 'giver'),
			'presentModel' => array(self::BELONGS_TO, 'Presents', 'present')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner' => 'Игрок',
			'present' => 'Подарок',
			'giver' => 'Даритель',
			'message' => 'Сообщение',
			'date' => 'Дата, время',
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
		$criteria->compare('present',$this->present);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('date',$this->date,true);

		if ($this->owner) {
			if (is_numeric($this->owner)) {
				$criteria->compare('owner',$this->owner);
			} else {
				$criteria->join .= " LEFT JOIN players op ON op.id = t.owner ";
				$criteria->compare('op.user', $this->owner, true);
			}
		}
		if ($this->giver) {
			if (is_numeric($this->giver)) {
				$criteria->compare('giver',$this->giver);
			} else {
				$criteria->join .= " LEFT JOIN players gp ON gp.id = t.owner ";
				$criteria->compare('gp.user', $this->giver, true);
			}
		}


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 25
			),
			'sort'=>array(
				'defaultOrder'=>'date DESC, owner, giver',
			)
		));
	}

	protected function afterSave()
	{
		if($this->isNewRecord)
		{
			if ($this->giver) {
				$gname = $this->giverModel->user;
			} else {
				$gname = "";
			}

			logdata_gift::add($this->owner,$this->giver, $gname, $this->presentModel->name);
		}
		return parent::afterSave();
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlayerPresents the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
