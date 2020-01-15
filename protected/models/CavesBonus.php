<?php

/**
 * This is the model class for table "{{caves_bonus}}".
 *
 * The followings are the available columns in table '{{caves_bonus}}':
 * @property integer $id
 * @property integer $gold
 * @property integer $platinum
 * @property integer $item_id
 * @property string $item_count
 */
class CavesBonus extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{caves_bonus}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gold, platinum, item_id, item_count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('gold, platinum, item_id, item_count', 'safe'),
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
            'coordinate_id' => 'ID',
            'gold' => 'Золото',
            'platinum' => 'Крышки',
            'item_id' => 'Предметы',
            'item_count' => 'Количество предметов',
        );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CavesBonus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function primaryKey()
    {
        return 'coordinate_id';
    }
}
