<?php

/**
 * This is the model class for table "{{chat_clan}}".
 *
 * The followings are the available columns in table '{{chat_clan}}':
 * @property integer $id
 * @property integer $player_id
 * @property string $message
 * @property string $dt
 * @property integer $to_player
 * @property integer $clan
 */
class RChat extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chat_clan}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('player_id, to_player, clan', 'numerical', 'integerOnly'=>true),
            array('message, dt', 'safe'),
        );
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'player_id' => 'Player',
            'message' => 'Message',
            'dt' => 'Dt',
            'to_player' => t::get('Лично'),
            'clan' => t::get('Клан')
        );
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chat the static model class
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
                $this->player_id = Yii::app()->stat->id;
                $this->clan = Yii::app()->stat->clan;
                $this->dt = new CDbExpression('NOW()');

                $mf = new MessageFormat();
                $this->message = $mf->format($this->message);
            }
            return true;
        }
        else
            return false;
    }


}