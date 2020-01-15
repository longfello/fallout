<?php

/**
 * This is the model class for table "{{language}}".
 *
 * The followings are the available columns in table '{{language}}':
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string $authItem
 * @property string $fallback
 * @property string $enable_game
 * @property string $enable_home
 * @property string $sort_order
 */
class Language extends CActiveRecord
{
  const DEFAULT_LANGUAGE = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{language}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('slug, fallback', 'length', 'max'=>5),
			array('enable_game, enable_home', 'length', 'max'=>1),
			array('name', 'length', 'max'=>50),
			array('authItem', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, slug, name, sort_order, fallback, enable_home, enable_game, authItem', 'safe', 'on'=>'search'),
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
			'id' => 'Идентификатор',
			'slug' => 'Псевдоним языка',
			'name' => 'Название языка',
			'authItem' => 'Право доступа',
			'fallback' => 'Запасной язык',
			'enable_game' => 'Разрешен для игры',
			'enable_home' => 'Разрешен для главной',
			'sort_order' => 'Порядок сортировки',
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
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('authItem',$this->authItem,true);
		$criteria->compare('fallback',$this->fallback,true);
		$criteria->compare('enable_game',$this->enable_game,true);
		$criteria->compare('enable_home',$this->enable_home,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Language the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public static function getWritableLanguages(){
    $languages = array();
    $models = Language::model()->findAll(array('order' => 'name'));
    foreach($models as $model){
      if (Yii::app()->user->checkAccess($model->authItem)) {
        $languages[$model->slug] = $model;
      }
    }
    return $languages;
  }

  public function afterSave(){
    Yii::app()->cache->flush();
  }

}
