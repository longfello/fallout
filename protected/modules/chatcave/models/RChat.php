<?php

/**
 * This is the model class for table "{{chat}}".
 *
 * The followings are the available columns in table '{{chat}}':
 * @property integer $id
 * @property integer $player_id
 * @property string $message
 * @property string $dt
 * @property integer $to_player
 * @property string $lang_slug
 */
class RChat extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chat_cave}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('player_id, to_player', 'numerical', 'integerOnly'=>true),
            array('message, dt', 'safe'),
            //array('message', 'filter', 'filter' => 'strip_tags'),
            //array('message', 'filter', 'filter' => array('MessageFormat', 'format')),
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
            'id' => 'ID',
            'player_id' => 'Player',
            'message' => 'Message',
            'dt' => 'Dt',
            'to_player' => t::get('Лично'),
            'lang_slug' => 'Language'
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

                $this->dt = new CDbExpression('NOW()');

                $mf = new MessageFormat();
                $this->message = $mf->format($this->message);
            }
            return true;
        }
        else
            return false;
    }


    public function defaultScope()
    {
        return array(

        );
    }

}