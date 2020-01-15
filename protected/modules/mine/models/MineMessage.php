<?php

/**
 * This is the model class for table "{{sulfur_mine_message}}".
 *
 * The followings are the available columns in table '{{sulfur_mine_message}}':
 * @property integer $message_id
 * @property string $message
 * @property integer $type
 * @property integer $hp_zero
 */
class MineMessage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mine_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message, type, hp_zero', 'required'),
			array('type, hp_zero', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>255),
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
			'message_id' => t::get('Message Id'),
			'message' => t::get('Message'),
			'type' => t::get('Type'),
			'hp_zero' => t::get('Hp Zero'),
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

		$criteria->compare('message_id',$this->message_id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('hp_zero',$this->hp_zero);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SulfurMineMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function primaryKey()
    {
        return 'message_id';
    }


    /**
     * Сообщение об успешном добытии серы
     * @param int $sulfurCount Количество добытой серы
     * @return string
     */
    public function getSuccessMessage($sulfurCount)
    {
        $messages = MineMessage::model()->findAll('type = :type', array(':type' => 1));
        shuffle($messages);
        $message = t::getDb('message', 'rev_mine_message', 'message_id', $messages[0]->message_id);
        $formattedMessage = $this->_replaceTextVariables($message, array('{N}' => $sulfurCount));
        return $formattedMessage;
    }


    /**
     * Замена переменных на реальные значения в текстовом сообщении
     * @param string $text Исходное сообщение с переменными
     * @param array $vars Переменные шаблона и их значения
     * @return string Сообщение с реальными значениями
     */
    private function _replaceTextVariables($text, $vars)
    {
        return strtr($text, $vars);
    }
}
