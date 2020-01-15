<?php

/**
 * This is the model class for table "{{chat_avatar}}".
 *
 * The followings are the available columns in table '{{chat_avatar}}':
 * @property integer $player_id
 * @property integer $avatar_id
 *
 * @property ChatAvatarBase $base
 */
class ChatAvatar extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chat_avatar}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('avatar_id', 'numerical', 'integerOnly'=>true),
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
	        'base' => [self::HAS_ONE, 'ChatAvatarBase', array('avatar_id' => 'avatar_id')],
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'player_id' => 'Player',
            'avatar_id' => 'Avatar',
        );
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChatAvatar the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}