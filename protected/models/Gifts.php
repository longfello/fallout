<?php

/**
 * This is the model class for table "{{gifts}}".
 *
 * The followings are the available columns in table '{{gifts}}':
 * @property string $player_id
 * @property string $items
 * @property integer $napad
 * @property integer $pohod
 * @property integer $pleft
 * @property string $text
 * @property string $text_en
 * @property string $dt
 */
class Gifts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gifts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, items, text, text_en', 'required'),
			array('napad, pohod, pleft', 'numerical', 'integerOnly'=>true),
			array('player_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('player_id, items, napad, pohod, pleft, text, text_en, dt', 'safe', 'on'=>'search'),
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
			'player_id' => 'Id игрока',
			'items' => 'Предметы',
			'napad' => 'Количество нападений',
			'pohod' => 'Количество походных очков',
			'pleft' => 'Количество дней стимулятора',
			'text' => 'Текст (рус)',
			'text_en' => 'Текст (англ)',
			'dt' => 'Время добавления',
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

		$criteria->compare('player_id',$this->player_id,true);
		$criteria->compare('items',$this->items,true);
		$criteria->compare('napad',$this->napad);
		$criteria->compare('pohod',$this->pohod);
		$criteria->compare('pleft',$this->pleft);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('text_en',$this->text_en,true);
		$criteria->compare('dt',$this->dt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gifts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
