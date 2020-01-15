<?php

/**
 * This is the model class for table "{{bank_history}}".
 *
 * The followings are the available columns in table '{{bank_history}}':
 * @property string $id
 * @property integer $from_player
 * @property integer $to_player
 * @property integer $gold
 * @property string $dt
 * @property string $type
 */
class BankHistory extends MLModel
{
	public $typesAvailable = [
		'city' => 'Water city',
		'toxiccave' => 'Токсические пещеры',
	];

	public $dt_start;
	public $dt_end;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bank_history}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from_player, to_player, gold, dt', 'required'),
			array('from_player, to_player, gold', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>9),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from_player, to_player, gold, dt, type, dt_start, dt_end', 'safe', 'on'=>'search'),
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
			'playerFromModel' => array(self::BELONGS_TO, 'Players', 'from_player'),
			'playerToModel' => array(self::BELONGS_TO, 'Players', 'to_player'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from_player' => 'От',
			'to_player' => 'Кому',
			'gold' => 'Золота',
			'dt' => 'Дата',
			'type' => 'Локация банка',
			'dt_start' => 'Дата (от)',
			'dt_end' => 'Дата (до)'
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

		$criteria->compare('id',$this->id,true);
		//$criteria->compare('from_player',$this->from_player);
		//$criteria->compare('to_player',$this->to_player);
		$criteria->compare('gold',$this->gold);
		$criteria->compare('dt',$this->dt,true);
		$criteria->compare('type',$this->type,true);

		if ($this->dt_start && !is_null($this->dt_start)) {
			$tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
			$criteria->addCondition("dt>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
		}

		if ($this->dt_end && !is_null($this->dt_end)) {
			$tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
			$criteria->addCondition("dt<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
		}

		if (!is_null($this->from_player)){
			if (is_numeric($this->from_player)) {
				$criteria->addCondition("from_player = {$this->from_player}");
			} else {
				if ($this->from_player) {
					$criteria->join .= " 
						LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->from_player}%') p1 ON p1.id = t.from_player
					";

					$criteria->addCondition("((p1.user IS NOT NULL))");
				}
			}
		}

		if (!is_null($this->to_player)){
			if (is_numeric($this->to_player)) {
				$criteria->addCondition("to_player = {$this->to_player}");
			} else {
				if ($this->to_player) {
					$criteria->join .= " 
						LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->to_player}%') p2 ON p2.id = t.to_player
					";

					$criteria->addCondition("((p2.user IS NOT NULL))");
				}
			}
		}

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

	public function getTypeName(){
		return isset($this->typesAvailable[$this->type])?$this->typesAvailable[$this->type]:'?';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BankHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
