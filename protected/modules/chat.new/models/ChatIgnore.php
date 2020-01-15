<?php

/**
 * This is the model class for table "{{chat_ignore}}".
 *
 * The followings are the available columns in table '{{chat_ignore}}':
 * @property integer $player_id
 * @property integer $ignore_player_id
 */
class ChatIgnore extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{chat_ignore}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, ignore_player_id', 'required'),
			array('player_id, ignore_player_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('player_id, ignore_player_id', 'safe', 'on'=>'search'),
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
			'player_id' => 'Player',
			'ignore_player_id' => 'Ignore Player',
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
		$criteria->compare('ignore_player_id',$this->ignore_player_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ChatIgnore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function primaryKey()
    {
        return array('player_id', 'ignore_player_id');
    }


    /**
     * Получить игроков, которые в игноре
     */
    public static function getIgnorePlayers()
    {
        $ignorePlayers = Yii::app()->db->createCommand()
            ->select('p.id, p.user')
            ->from('{{chat_ignore}} ci')
            ->join('players p', 'ci.ignore_player_id=p.id')
            ->where('ci.player_id = :playerId', array(':playerId' => Yii::app()->stat->id))
            ->queryAll();

        return $ignorePlayers;
    }
}
