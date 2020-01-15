<?php

/**
 * This is the model class for table "utatoos".
 *
 * The followings are the available columns in table 'utatoos':
 * @property integer $id
 * @property integer $owner
 * @property integer $regens
 * @property integer $tatoo
 * @property integer $lifetime
 * @property integer $timeout
 */
class Utatoos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'utatoos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner, tatoo', 'required'),
			array('owner, regens, tatoo, lifetime, timeout', 'numerical', 'integerOnly'=>true),
			array('tatoo', 'checkTatooExist'),
			array('owner', 'checkOwnerExist'),
			array('owner', 'checkOwnerLevel'),
			array('tatoo', 'checkTatooPersonal'),
			array('owner', 'checkOwnerStat'),
			array('owner', 'checkOwnerMoney'),
			array('owner', 'checkTatooCount', 'tatoo_col'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner, regens, tatoo, lifetime, timeout', 'safe', 'on'=>'search'),
		);
	}

	public function checkTatooExist($attribute,$params)
	{
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);

		if (!$tatoo_model) {
			$this->addError($attribute, 'Такой татуировки не существует!!!');
		}
	}

	public function checkTatooPersonal($attribute,$params) {
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);
		$player_model = Players::model()->findByPk($this->owner);

		if ($tatoo_model->owner!=0 && $tatoo_model->owner!=$this->owner) {
			$this->addError($attribute, 'Персональная татуировка другого игрока!!!');
		}

		if ($tatoo_model->clan!=0 && $player_model->clan!=$this->clan) {
			$this->addError($attribute, 'Татуировка другого клана!!!');
		}
	}

	public function checkTatooCount($attribute,$params) {
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);

		if ($tatoo_model->count != 'unlim') {
			$sql = "SELECT
					COUNT(ut.id)
				FROM
					utatoos ut
				WHERE ut.`owner`='{$this->owner}' AND ut.`tatoo`='{$this->tatoo}'";
			$owner_tatoos_count = intval(Yii::app()->db->createCommand($sql)->queryScalar());
			if ($owner_tatoos_count>0) {
				$this->addError($attribute, 'Игрок можете набить только одну такую татуировку!');
			}
		} else {
			$type_str = '';
			$where = '1';

			if ($tatoo_model->clan!=0) {
				$where = 't.clan!=0';
				$type_str = 'клановых';
			} elseif ($tatoo_model->owner!=0) {
				$where = 't.owner!=0';
				$type_str = 'личных';
			} else {
				$where = 't.clan=0 AND t.owner=0';
				$type_str = 'обычных';
			}
			$sql = "SELECT
					COUNT(ut.id)
				FROM
					utatoos ut
				INNER JOIN
				tatoos t ON t.id=ut.tatoo
				WHERE ut.`owner`='{$this->owner}' AND $where";
			$owner_tatoos_count = intval(Yii::app()->db->createCommand($sql)->queryScalar());

			if ($owner_tatoos_count>=$params['tatoo_col']) {
				$this->addError($attribute, 'У игрока набито максимум '.$type_str.' татуировок (можно не больше '.$params['tatoo_col'].')');
			}
		}
	}

	public function checkOwnerExist($attribute,$params)
	{
		$player_model = Players::model()->findByPk($this->owner);

		if (!$player_model) {
			$this->addError($attribute, 'Такого игрока не существует!!!');
		}
	}

	public function checkOwnerLevel($attribute,$params) {
		$player_model = Players::model()->findByPk($this->owner);
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);

		if ($player_model->level<$tatoo_model->minlev) {
			$this->addError($attribute, 'Данная татуировка доступна только с '.$tatoo_model->minlev.' уровня игрока!!!');
		}
	}

	public function checkOwnerStat($attribute,$params) {
		$player_model = Players::model()->findByPk($this->owner);
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);

		foreach ($tatoo_model->min_player_attributes as $attr) {
			$tattr = 'min_'.$attr;
			$tatoo_attr_val = (int)$tatoo_model->getAttribute($tattr);
			$player_attr_val = (int)$player_model->getAttribute($attr);
			if ($tatoo_attr_val>$player_attr_val) {
				$this->addError($attribute, 'У игрока недостаточно статов '.$attr.' для использования татуировки!!!');
			}
		}
	}

	public function checkOwnerMoney($attribute,$params)
	{
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);
		$player_model = Players::model()->findByPk($this->owner);
		if ($tatoo_model->mtype == 'gold') {
			if (($player_model->gold < $tatoo_model->cost && $player_model->bank < $tatoo_model->cost)) {
				$this->addError($attribute, 'У пользователя не хватает денег на оплату татуировки!!!');
			}
		} elseif ($tatoo_model->mtype == 'platinum') {
			if ($player_model->platinum < $tatoo_model->cost) {
				$this->addError($attribute, 'У пользователя не хватает крышек на оплату татуировки!!!');
			}
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tatooModel' => array(self::BELONGS_TO, 'Tatoos', 'tatoo'),
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
			'owner' => 'Владелец',
			'regens' => 'Регенераций',
			'tatoo' => 'Татуировка',
			'lifetime' => 'Действует до даты',
			'timeout' => 'Истекла',
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
		$criteria->compare('owner',$this->owner);
		$criteria->compare('regens',$this->regens);
		$criteria->compare('tatoo',$this->tatoo);
		$criteria->compare('lifetime',$this->lifetime);
		$criteria->compare('timeout',$this->timeout);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Utatoos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$tatoo_model = Tatoos::model()->findByPk($this->tatoo);
				$player_model = Players::model()->findByPk($this->owner);

				if ($tatoo_model && $player_model) {
					$this->regens = $tatoo_model->regen_count;
					$this->lifetime = ($tatoo_model->days > 0) ? $tatoo_model->days * 86400 + time() : 0;

					if ($tatoo_model->mtype == 'gold') {
						if ($player_model->gold >= $tatoo_model->cost) {
							$player_model->gold -= $tatoo_model->cost;
						} elseif ($player_model->bank >= $tatoo_model->cost) {
							$player_model->bank -= $tatoo_model->cost;
						}
					} elseif ($tatoo_model->mtype == 'platinum') {
						$player_model->platinum -= $tatoo_model->cost;
					}

					foreach ($tatoo_model->change_player_attributes as $attr) {
						$tattr = 'add_'.$attr;
						$tatoo_attr_val = (int)$tatoo_model->getAttribute($tattr);
						$player_attr_val = (int)$player_model->getAttribute($attr);
						if ($tatoo_attr_val>0) {
							$player_model->setAttribute($attr, $player_attr_val + $tatoo_attr_val);
						}
					}
					$player_model->save(false);
				}
			}
			return true;
		}
		else
			return false;
	}


	protected function beforeDelete()
	{
		$player_model = Players::model()->findByPk($this->owner);
		$tatoo_model = Tatoos::model()->findByPk($this->tatoo);
		if ($player_model && $tatoo_model) {
			if ($this->timeout==0) {
				foreach ($tatoo_model->change_player_attributes as $attr) {
					$tattr = 'add_'.$attr;
					$tatoo_attr_val = (int)$tatoo_model->getAttribute($tattr);
					$player_attr_val = (int)$player_model->getAttribute($attr);
					if ($tatoo_attr_val>0) {
						$player_model->setAttribute($attr, $player_attr_val - $tatoo_attr_val);
					}
				}
				$player_model->save(false);
			}
		}
		return parent::beforeDelete();
	}
}
