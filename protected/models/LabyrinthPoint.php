<?php

/**
 * This is the model class for table "labyrinthrev".
 *
 * The followings are the available columns in table 'labyrinthrev':
 * @property integer $coordinate_id
 * @property integer $y
 * @property integer $x
 * @property integer $move
 * @property string $polygon_image
 */
class LabyrinthPoint extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{labyrinth_point}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('y, x, move, polygon_image', 'required'),
			array('y, x, move', 'numerical', 'integerOnly'=>true),
			array('polygon_image', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('coordinate_id, y, x, move, polygon_image', 'safe', 'on'=>'search'),
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
			'coordinate_id' => 'Coordinate',
			'y' => 'Y',
			'x' => 'X',
			'move' => 'Move',
			'polygon_image' => 'Polygon Image',
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

		$criteria->compare('coordinate_id',$this->coordinate_id);
		$criteria->compare('y',$this->y);
		$criteria->compare('x',$this->x);
		$criteria->compare('move',$this->move);
		$criteria->compare('polygon_image',$this->polygon_image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LabyrinthPoint the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
