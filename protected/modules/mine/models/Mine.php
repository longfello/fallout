<?php

/**
 * This is the model class for table "{{sulfur_mine}}".
 *
 * The followings are the available columns in table '{{sulfur_mine}}':
 * @property integer $player_id
 * @property integer $count_ways
 * @property string $time
 * @property integer $is_donate
 * @property string $paid_donate_time
 */
class Mine extends CActiveRecord
{
    private $_model;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mine}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, count_ways, time', 'required'),
			array('player_id, count_ways', 'numerical', 'integerOnly'=>true)
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
			'player_id' => t::get('Player'),
			'count_ways' => t::get('Count Ways'),
			'time' => t::get('Time'),
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

		$criteria->compare('player_id',$this->player_id);
		$criteria->compare('count_ways',$this->count_ways);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SulfurMine the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function primaryKey()
    {
        return 'player_id';
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $this->player_id = Yii::app()->stat->id;
            }
            return true;
        }
        else
            return false;
    }


    /**
     * Загрузка модели таблицы Mine
     */
    private function _loadModel()
    {
        if($this->_model === null)
        {

            $this->_model = Mine::model()->findByPk(Yii::app()->stat->id);
            if (!$this->_model)
            {
                $this->_model = new Mine();
            }
        }
        return $this->_model;
    }



    /**
     * Добавление серы игроку в инвентарь
     * @return int Количество полученной серы
     */
    public function addSulfur()
    {
        $count = mt_rand(Yii::app()->params['mine']['minDrop'], Yii::app()->params['mine']['maxDrop']);

        $item = new Uequipment();
        for ($i = 1; $i <= $count; $i++)
        {
            $item->isNewRecord = true;
            $item->id = null;
            $item->owner = Yii::app()->stat->id;
            $item->item = Yii::app()->params['mine']['item'];
            $item->save(false);
        }

        return $count;
    }


    /**
     * Получить количество ходов, сколько может сделать игрок
     */
    public function getAvailableWays()
    {
        if ($this->_isPaidDonate())
        {
            return Yii::app()->params['mine']['paidWays'] + Yii::app()->params['mine']['defaultWays'] + $this->_getExtraWayDependencyLevel();
        }

        return Yii::app()->params['mine']['defaultWays'] + $this->_getExtraWayDependencyLevel();
    }


    /**
     * Проверить: купил ли игрок донат на увелечение количества работ на руднике
     */
    private function _isPaidDonate()
    {
        $mine = $this->model()->findBypk(Yii::app()->stat->id);
        if ($mine && $mine->is_donate)
            return true;
    }


    /**
     * Доступны ли серные рудники для работы
     * @return boolean True - если доступны. False - если нет
     */
    /*public function isAvailableMine()
    {
        $sulfurMine = Mine::model()->findByAttributes(array('player_id' => Yii::app()->stat->id, 'time' => date('Y-m-d')));
        if (!$sulfurMine)
            return true;
        elseif ($sulfurMine->count_ways < $sulfurMine->getAvailableWays())
            return true;
    }*/



    /**
     * Получить оставшееся количество ходов
     */
    public function getRemainingWays()
    {
        // Тут надо получить новые данные
        $mine = $this->_loadModel();

        $count = $this->getAvailableWays() - $mine->count_ways;
        if ($count > 0)
            return $count;
        else
            return 0;
    }


    /**
     * Есть у игрока кирка
     */
    public function hasPick()
    {
        $uequipment = Uequipment::model()->findByAttributes(array('owner' => Yii::app()->stat->id, 'item' => 997));
        if ($uequipment)
            return true;
    }


    /**
     * Надета ли кирка
     */
    public function isTakenPick()
    {
        $uequipment = Uequipment::model()->findByAttributes(array('owner' => Yii::app()->stat->id, 'item' => 997, 'status' => 'E'));
        if ($uequipment)
            return true;
    }


    /**
     * Доступен ли донат текущему игроку
     */
    public function isAvailableDonate()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'TIMESTAMPDIFF(DAY, paid_donate_time, NOW()) <= 0';
        $criteria->addCondition('player_id = ' . Yii::app()->stat->id);
        $mine = Mine::model()->find($criteria);
        if (!$mine)
            return true;
    }


    /**
     * Достаточно ли игрока денег для покупки увеличения ходов
     */
    public function isEnoughPlatinum()
    {
        if (Yii::app()->stat->platinum >= Yii::app()->params['mine']['costWays'])
            return true;
    }



    /**
     * Добавить ход о работе на серном руднике
     */
    public function addWorkWay()
    {
        $mine = $this->_loadModel();

        $mine->count_ways++;
        $mine->save(false);
    }


    /**
     * Дополнительный ходы в зависимости от уровня
     */
    private function _getExtraWayDependencyLevel()
    {
        $level = ExperienceWorker::model()->getLevel();

        $gradation = array(0, 1, 2, 4, 6, 8, 11, 14, 18, 22, 30);

        return $gradation[$level];
    }


    /**
     * Обнулить количество ходов на серном руднике, если прошли сутки
     */
    public function resetWorkWay()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = new CDbExpression("NOT time = DATE_FORMAT(NOW(), '%Y-%m-%d')");
        $criteria->addCondition('player_id = ' . Yii::app()->stat->id);
        $sulfurMine = $this->find($criteria);

        if ($sulfurMine)
        {
            $sulfurMine->count_ways = 0;
            $sulfurMine->is_donate = 0;
            $sulfurMine->time = new CDbExpression('NOW()');
            $sulfurMine->save(false);
        }

        $this->_model = null;
    }


    /**
     * Создать записи о игроке в пещерах
     */
    public function createData()
    {
	    $model = $this->_loadModel();
	    $model->player_id = Yii::app()->stat->id;
	    $model->save(false);
    }
}
