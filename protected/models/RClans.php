<?php

/**
 * This is the model class for table "clans".
 *
 * The followings are the available columns in table 'clans':
 * @property integer $id
 * @property string $name
 * @property integer $owner
 * @property integer $coowner
 * @property integer $gold
 * @property integer $platinum
 * @property string $public_msg
 * @property string $private_msg
 * @property string $tag
 * @property string $pass
 * @property string $hospass
 * @property string $clan_tax_ranges
 * @property integer $war_win
 * @property integer $union_win
 */
class RClans extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'clans';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, public_msg, private_msg, tag, pass, clan_tax_ranges, war_win, union_win', 'required'),
			array('owner, coowner, gold, platinum, war_win, union_win', 'numerical', 'integerOnly'=>true),
			array('name, clan_tax_ranges', 'length', 'max'=>255),
			array('pass', 'length', 'max'=>30),
			array('hospass', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, owner, coowner, gold, platinum, public_msg, private_msg, tag, pass, hospass, clan_tax_ranges, war_win, union_win', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'owner' => 'Owner',
			'coowner' => 'Coowner',
			'gold' => 'Gold',
			'platinum' => 'Platinum',
			'public_msg' => 'Public Msg',
			'private_msg' => 'Private Msg',
			'tag' => 'Tag',
			'pass' => 'Pass',
			'hospass' => 'Hospass',
			'clan_tax_ranges' => 'Clan Tax Ranges',
			'war_win' => 'War Win',
			'union_win' => 'Union Win',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('owner',$this->owner);
		$criteria->compare('coowner',$this->coowner);
		$criteria->compare('gold',$this->gold);
		$criteria->compare('platinum',$this->platinum);
		$criteria->compare('public_msg',$this->public_msg,true);
		$criteria->compare('private_msg',$this->private_msg,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('pass',$this->pass,true);
		//$criteria->compare('moneypass',$this->moneypass,true);
		$criteria->compare('hospass',$this->hospass,true);
		//$criteria->compare('clanwars',$this->clanwars,true);
		//$criteria->compare('chatroom',$this->chatroom,true);
		//$criteria->compare('clanstore',$this->clanstore,true);
		$criteria->compare('clan_tax_ranges',$this->clan_tax_ranges,true);
		$criteria->compare('war_win',$this->war_win);
		$criteria->compare('union_win',$this->union_win);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Clans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
