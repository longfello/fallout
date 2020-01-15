<?php

/**
 * This is the model class for table "toxstore".
 *
 * The followings are the available columns in table 'toxstore':
 * @property integer $id
 * @property integer $item
 * @property integer $player
 * @property string $damaged
 * @property string $creationdate
 */
class Toxstore extends Store
{
  const ITEMS_LIMITATION = 100;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'toxstore';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item, player, creationdate', 'required'),
			array('item, player', 'numerical', 'integerOnly'=>true),
			array('damaged', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, item, player, damaged, creationdate', 'safe', 'on'=>'search'),
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
			'id' =>  t::get('ID'),
			'item' =>  t::get('Item'),
			'player' =>  t::get('Player'),
			'damaged' =>  t::get('Damaged'),
			'creationdate' =>  t::get('Creationdate'),
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
		$criteria->compare('item',$this->item);
		$criteria->compare('player',$this->player);
		$criteria->compare('damaged',$this->damaged,true);
		$criteria->compare('creationdate',$this->creationdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Toxstore the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

  public function beforeSave() {
    if ($this->isNewRecord) {
      if (!$this->player) $this->player = Yii::app()->stat->id;
      $this->creationdate = new CDbExpression('NOW()');
    }

    return parent::beforeSave();
  }

  public function putItems($ids = array()){
    $count = $this->countByAttributes(array('player' => Yii::app()->stat->id));
    $limit = self::ITEMS_LIMITATION - $count;
    if ($limit > 0) {
      parent::putItems($ids);
      $tablename = $this->correctIds($ids);
      $idsCondition = $ids?" AND id IN (SELECT id FROM {$tablename})":"";
      $query = "
INSERT INTO toxstore (item, player, damaged, creationdate)(
select item, `owner`, damaged, NOW() FROM uequipment
WHERE `owner` = ".Yii::app()->stat->id." AND `status` = '".UequipmentStatusEnum::UNEQUIPPED."' $idsCondition
ORDER BY item
LIMIT $limit;
)";

      $cmd    = Yii::app()->getDb()->commandBuilder;
      $putted = $cmd->createSqlCommand($query)->execute();

      $query = "DELETE FROM uequipment WHERE `owner` = ".Yii::app()->stat->id." AND `status` = '".UequipmentStatusEnum::UNEQUIPPED."' $idsCondition";
      $cmd->createSqlCommand($query)->execute();

      if ($putted == $limit) {
        Messages::flash(Messages::MSG_NOTICE, t::get("Кладовая в пещерах ограничена по размерам. Вы можете хранить здесь бесплатно 100 единиц любых предметов."));
      }
      Messages::flash(Messages::MSG_INFO, t::get("Предметы сданы в кладовку (%s).", $putted));
    } else {
      Messages::flash(Messages::MSG_NOTICE, t::get("Кладовая в пещерах ограничена по размерам. Вы можете хранить здесь бесплатно 100 единиц любых предметов."));
    }
  }

}
