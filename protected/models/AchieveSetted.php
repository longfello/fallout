<?php

/**
 * This is the model class for table "{{achieve_setted}}".
 *
 * The followings are the available columns in table '{{achieve_setted}}':
 * @property integer $user_id
 * @property integer $achieve_id
 * @property string $date
 */
class AchieveSetted extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{achieve_setted}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, achieve_id', 'required'),
			array('user_id, achieve_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, achieve_id', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'achieve_id' => 'Achieve',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('achieve_id',$this->achieve_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AchieveSetted the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function primaryKey() {
        return array('user_id', 'achieve_id');
    }


    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            $this->date = new CDbExpression('NOW()');

            return true;
        }
        else
            return false;
    }



    /**
     * Проверить: установлена ли уже эта ачивка
     * @param $achieveId int Id ачивки
     * @return boolean
     */
    public static function check($achieveId)
    {
        return self::model()->findByPk(array('user_id' => Yii::app()->stat->id, 'achieve_id' => $achieveId));
    }

}
