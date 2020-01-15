<?php

/**
 * This is the model class for table "{{invite_soc}}".
 *
 * The followings are the available columns in table '{{invite_soc}}':
 * @property string $id
 * @property string $owner
 * @property string $social
 * @property string $ip
 * @property string $created_at
 * @property integer $caps
 */
class InviteSoc extends CActiveRecord
{
    public $dt_start;
    public $dt_end;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{invite_soc}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner, social, ip', 'required'),
			array('caps', 'numerical', 'integerOnly'=>true),
			array('owner', 'length', 'max'=>10),
			array('social', 'length', 'max'=>255),
			array('ip', 'length', 'max'=>11),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner, social, ip, created_at, caps, dt_start, dt_end', 'safe', 'on'=>'search'),
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
            'ownerModel' => array(self::BELONGS_TO, 'Players', 'owner'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner' => 'Владелец ссылки',
			'social' => 'Соц. сеть',
			'ip' => 'IP адрес посетителя',
			'created_at' => 'Дата входа',
			'caps' => 'Крышек начисленно',
            'dt_start' => 'Дата входа (от)',
            'dt_end' => 'Дата входа (до)'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('owner',$this->owner,true);
		$criteria->compare('social',$this->social,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('caps',$this->caps);

        if ($this->dt_start && !is_null($this->dt_start)) {
            $tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
            $criteria->addCondition("created_at>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
        }

        if ($this->dt_end && !is_null($this->dt_end)) {
            $tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
            $criteria->addCondition("created_at<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'Pagination' => array (
                'PageSize' => 100
            ),
            'sort'=>array(
                'defaultOrder'=>'created_at DESC',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InviteSoc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
