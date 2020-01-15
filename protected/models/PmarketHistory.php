<?php

/**
 * This is the model class for table "pmarket_history".
 *
 * The followings are the available columns in table 'pmarket_history':
 * @property integer $id
 * @property integer $seller
 * @property integer $buyer
 * @property integer $cost
 * @property integer $count
 * @property string $dt
 */
class PmarketHistory extends MLModel
{

	public $dt_start;
	public $dt_end;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pmarket_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seller, buyer, cost, count, dt', 'required'),
			array('seller, buyer, cost, count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, seller, buyer, cost, count, dt, dt_start, dt_end', 'safe', 'on'=>'search'),
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
			'sellerModel' => array(self::BELONGS_TO, 'Players', 'seller'),
			'buyerModel' => array(self::BELONGS_TO, 'Players', 'buyer'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'seller' => 'Продавец',
			'buyer' => 'Покупатель',
			'cost' => 'Цена в золотое',
			'count' => 'Количество купленных крышек',
			'dt' => 'Дата покупки',
			'dt_start' => 'Дата покупки (от)',
			'dt_end' => 'Дата покупки (до)'
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
		//$criteria->compare('seller',$this->seller);
		//$criteria->compare('buyer',$this->buyer);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('count',$this->count);
		$criteria->compare('dt',$this->dt,true);

		if ($this->dt_start && !is_null($this->dt_start)) {
			$tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
			$criteria->addCondition("dt>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
		}

		if ($this->dt_end && !is_null($this->dt_end)) {
			$tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
			$criteria->addCondition("dt<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
		}

		if (!is_null($this->seller)){
			if (is_numeric($this->seller)) {
				$criteria->addCondition("seller = {$this->seller}");
			} else {
				if ($this->seller) {
					$criteria->join .= " 
						LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->seller}%') p1 ON p1.id = t.seller
					";

					$criteria->addCondition("((p1.user IS NOT NULL))");
				}
			}
		}

		if (!is_null($this->buyer)){
			if (is_numeric($this->buyer)) {
				$criteria->addCondition("buyer = {$this->buyer}");
			} else {
				if ($this->buyer) {
					$criteria->join .= " 
						LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->buyer}%') p2 ON p2.id = t.buyer
					";

					$criteria->addCondition("((p2.user IS NOT NULL))");
				}
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 50
			),
			'sort'=>array(
				'defaultOrder'=>'dt DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PmarketHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
