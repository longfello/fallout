<?php

/**
 * This is the model class for table "{{language_translate_home}}".
 *
 * The followings are the available columns in table '{{language_translate_home}}':
 * @property integer $id
 * @property integer $lang_id
 * @property string $slug
 * @property string $value
 * @property integer $translated
 */
class LanguageTranslateHome extends CActiveRecord
{
	public $translated;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{language_translate_home}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lang_id, slug', 'required'),
			array('id, lang_id', 'numerical', 'integerOnly'=>true),
			array('slug', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lang_id, slug, value, translated', 'safe'),
			array('id, lang_id, slug, value, translated', 'safe', 'on'=>'search'),
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
			'lang_id' => 'Язык',
			'slug' => 'Псевдоним исходной строки',
			'value' => 'Перевод',
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
		$criteria->compare('lang_id',$this->lang_id);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('value',$this->value,true);

		if ($this->translated == 1){
			$criteria->compare('(1*((`slug`=`value` AND lang_id<>1) || (`value` =\'\')))',$this->translated,false);
		}

    $models = Language::getWritableLanguages();
    $langs  = array();
    foreach($models as $model) {
      $langs[] = $model->id;
    }
    $criteria->addInCondition('lang_id', $langs);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination'=>array(
          'pageSize'=>50,
      ),
    ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LanguageTranslate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function afterSave(){
    if (isset(Yii::app()->cache)) Yii::app()->cache->flush();
  }

  public function afterDelete(){
    $models = self::model()->findAllByAttributes(array('slug' => $this->slug));
    foreach($models as $model) {
      if ($model->id != $this->id) $model->delete(false);
    }
  }

	public function getIsTranslated(){
		return ((((strtolower($this->value) == strtolower($this->slug)) || (empty($this->value))) && ($this->lang_id != 1)));
	}

}
