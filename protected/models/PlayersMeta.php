<?php

/**
 * This is the model class for table "players_meta".
 *
 * The followings are the available columns in table 'players_meta':
 * @property integer $id
 * @property integer $player_id
 * @property string $key
 * @property integer $value
 */
class PlayersMeta extends CActiveRecord
{
    const KEY_LISA = 'lisa';
    const KEY_MAIL = 'exchange_mailing';
	const KEY_LAST_KILLED_BY = 'last_killed_by';
	const KEY_LAST_KILLED = 'last_killed';

	const KEY_NKR_DOLLARS_TOTAL = 'nkr_dollars_total';
	const KEY_NKR_DOLLARS_SPENT = 'nkr_dollars_spent';
	const KEY_NKR_DOLLARS_IDS   = 'nkr_dollars_ids';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'players_meta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('player_id, key, value', 'required'),
			array('player_id', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>255),
			array('key', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, player_id, key, value', 'safe', 'on'=>'search'),
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
	    switch ($this->key){
	      case self::KEY_LISA:
	        $value = 'Нагрубил Лизе?';
	        break;
	      case self::KEY_MAIL:
	        $value = 'Присылать уведомления о новых объявлениях?';
	        break;
	      default:
		    $value = $this->key;
	    }
		return array(
				'id' => 'ID',
				'player_id' => 'Player ID',
				'key' => 'Key',
				'value' => $value,
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
		$criteria->compare('player_id',$this->player_id);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('value',$this->value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlayersMeta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
