<?php

/**
 * This is the model class for table "{{experience_worker}}".
 *
 * The followings are the available columns in table '{{experience_worker}}':
 * @property integer $user_id
 * @property integer $garbage
 * @property integer $mine
 */
class ExperienceWorker extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{experience_worker}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, garbage, mine', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, garbage, mine', 'safe', 'on'=>'search'),
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
			'garbage' => 'Garbage',
			'mine' => 'Mine',
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
		$criteria->compare('garbage',$this->garbage);
		$criteria->compare('mine',$this->mine);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RevExperienceWorker the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function scopes()
    {
        return array(
            'current' => array(
                'condition' => 'user_id = ' . Yii::app()->stat->id,
            ),
        );
    }


    /**
     * Загрузка модели
     */
    public static function loadModel()
    {
        $experienceWorker = ExperienceWorker::model()->findByPk(Yii::app()->stat->id);
        if (!$experienceWorker)
        {
            $experienceWorker = new ExperienceWorker();
            $experienceWorker->user_id += Yii::app()->stat->id;
        }

        return $experienceWorker;
    }

    /**
     * Добавить опыт в базу
     * @param $count Количество добавляемого опыта
     */
    public static function addExperience($count)
    {
        $experienceWorker = ExperienceWorker::loadModel();

        $experienceWorker->mine += $count;
        $experienceWorker->save(false);
    }




    /**
     * Получить уровень шахтера
     * @return int
     */
    public function getLevel()
    {
        $experienceWorker = self::model()->current()->find();

        $number = $experienceWorker ? $experienceWorker->mine : 0;

        if ($number < 50) {
            return 0;
        }
        elseif ($number >= 50 && $number < 450) {
            return 1;
        }
        elseif ($number >= 450 && $number < 1150) {
            return 2;
        }
        elseif ($number >= 1150 && $number < 2350) {
            return 3;
        }
        elseif ($number >= 2350 && $number < 4550) {
            return 4;
        }
        elseif ($number >= 4550 && $number < 7950) {
            return 5;
        }
        elseif ($number >= 7950 && $number < 12950) {
            return 6;
        }
        elseif ($number >= 12950 && $number < 19950) {
            return 7;
        }
        elseif ($number >= 19950 && $number < 29950) {
            return 8;
        }
        elseif ($number >= 29950 && $number < 44950) {
            return 9;
        }
        elseif ($number >= 44950) {
            return 10;
        }
    }

}
