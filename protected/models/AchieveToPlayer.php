<?php

/**
 * This is the model class for table "{{achieve_to_player}}".
 *
 * The followings are the available columns in table '{{achieve_to_player}}':
 * @property integer $user_id
 * @property string $achieve_type
 * @property integer $count
 */
class AchieveToPlayer extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{achieve_to_player}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, achieve_type, count', 'required'),
			array('user_id, count', 'numerical', 'integerOnly'=>true),
			array('achieve_type', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, achieve_type, count', 'safe', 'on'=>'search'),
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
			'achieve_type' => 'Achieve Type',
			'count' => 'Count',
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
		$criteria->compare('achieve_type',$this->achieve_type,true);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AchieveToPlayer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function primaryKey() {
        return array('user_id', 'achieve_type');
    }


    public function defaultScope()
    {
        return array(
            'condition' => "user_id='" . Yii::app()->stat->id . "'",
        );
    }


    /**
     * Загрузка модели
     */
    public function loadModel($type, $player_id = false)
    {
      $model = AchieveToPlayer::model();
      $model->resetScope();
      $criteria = new CDbCriteria();
      $criteria->addCondition("achieve_type = '$type'");
      if ($player_id) {
        $criteria->addCondition("user_id='{$player_id}'");
      } else {
        $player_id = Yii::app()->stat->id;
        $criteria->addCondition("user_id='".Yii::app()->stat->id."'");
      }
        $achieveToPlayer = $model->find($criteria);
        if (!$achieveToPlayer)
        {
            $achieveToPlayer = new AchieveToPlayer();
            $achieveToPlayer->user_id = $player_id;
            $achieveToPlayer->achieve_type = $type;
        }
        return $achieveToPlayer;
    }


    /**
     * Добавление одной единицы ачивки
     * @param $model AchieveToPlayer
     */
    public function addExperience($model, $count = 1)
    {
        $model->count += $count;
        $model->save(false);
    }


    /**
     * Установить ачивку
     * @param $type Тип ачивки
     */
    public static function set($type)
    {
        /** @var $experienceWorker ExperienceWorker */
        $experienceWorker = ExperienceWorker::model()->current()->find();

        if ($experienceWorker && $level = AchieveRules::$type($experienceWorker->mine))
        {
            /** @var Achieve $achieve */
            $achieve = Achieve::model()->findByAttributes(array('type' => $type, 'level' => $level));

            // Проверяем: установлено ли уже эта ачивка игроку
            if (!AchieveSetted::check($achieve->id))
            {
                $achieveSetted = new AchieveSetted();
                $achieveSetted->user_id = Yii::app()->stat->id;
                $achieveSetted->achieve_id = $achieve->id;
                $achieveSetted->save(false);

							  logdata_new_achieve::add(Yii::app()->stat->id, $achieve->id);
                // Добавить сообшение в лог
                // $achieve->name = t::getDb('name', 'rev_achieve', 'id', $achieve->id);
                // RLog::add(t::get('Получено новое достижение: "%s"', $achieve->name), Yii::app()->stat->id);
            }
        }
    }
}
