<?php

/**
 * This is the model class for table "pet_exp".
 *
 * The followings are the available columns in table 'pet_exp':
 * @property integer $level
 * @property integer $er
 * @property integer $er1
 * @property integer $er2
 */
class PetExp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pet_exp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('level, er, er1, er2', 'required'),
			array('level, er, er1, er2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('level, er, er1, er2', 'safe', 'on'=>'search'),
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
			'level' => 'Level',
			'er' => 'Er',
			'er1' => 'Er1',
			'er2' => 'Er2',
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

		$criteria->compare('level',$this->level);
		$criteria->compare('er',$this->er);
		$criteria->compare('er1',$this->er1);
		$criteria->compare('er2',$this->er2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PetExp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public static function getExperienceForNextLevel($current_level){
    $criteria = new CDbCriteria();
    $criteria->addCondition("level <= :level");
    $criteria->params['level'] = $current_level;
    $criteria->order = "level DESC";
    $criteria->limit = 1;

    /** @var $model PetExp */
    $model = PetExp::model()->find($criteria);

    $expn_pet = (($current_level * $model->er1) + ($current_level * $model->er2) * ($current_level) * $model->er) / 4;

    return $expn_pet;
  }
}
