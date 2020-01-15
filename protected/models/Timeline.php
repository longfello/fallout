<?php

/**
 * This is the model class for table "{{timeline}}".
 *
 * The followings are the available columns in table '{{timeline}}':
 * @property integer $id
 * @property string $category
 * @property string $event
 * @property string $data
 * @property integer $created_at
 */
class Timeline extends CActiveRecord
{
  const cSECURITY = 'security';
  const cPLAYER   = 'player';

  const eCHANGE   = 'change';
  const eREGISTER = 'register';
  const eHACK     = 'hack';
  const eGIFT     = 'gift';
  const eRemove     = 'remove';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{timeline}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category, event, created_at', 'required'),
			array('created_at', 'numerical', 'integerOnly'=>true),
			array('category, event', 'length', 'max'=>64),
			array('data', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, category, event, data, created_at', 'safe', 'on'=>'search'),
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
			'category' => 'Category',
			'event' => 'Event',
			'data' => 'Data',
			'created_at' => 'Created At',
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
		$criteria->compare('category',$this->category,true);
		$criteria->compare('event',$this->event,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Timeline the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public static function getData(){
    $criteria = new CDbCriteria();
    $criteria->order = "created_at DESC";
    $criteria->limit = 12;
    $data = array();
    foreach(self::model()->findAll($criteria) as $model) {
      $data[] = array(
          'model' => $model
        /*
          'iconOptions' => array(),
          'iconImage' => 'http://example.com/image.jpg',
          'iconImageOptions' => array(),
          'iconLink' => 'http://example.com',
          'iconLinkOptions' => array(),
          'iconHtml' => '<b>42</b>',
        */
      );
    }
    return $data;
  }

  public function beforeSave(){
    $this->created_at = time();
    return parent::beforeSave();
  }

  public static function add($category, $event, $data, $title = 'Новое событие', $icon = '<i class="fa fa-evenlope bg-blue"></i>', $footer = ''){
    $data = (array)$data;
    $data['title'] = $title;
    $data['icon']  = $icon;
    $data['footer']  = $footer;

    $model = new self;
    $model->category = $category;
    $model->event    = $event;
    $model->data     = json_encode($data);
    $model->created_at = time();
    $model->save();
  }
}
