<?php

/**
 * This is the model class for table "player_appearance".
 *
 * The followings are the available columns in table 'player_appearance':
 * @property integer $player_id
 * @property integer $appearance_layout_id
 * @property integer $player_race_appearance_id
 *
 * @property appearanceLayout $layout
 * @property playerRaceAppearanceList $image
 */
class PlayerAppearance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'player_appearance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, appearance_layout_id, player_race_appearance_id', 'required'),
			array('player_id, appearance_layout_id, player_race_appearance_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('player_id, appearance_layout_id, player_race_appearance_id', 'safe', 'on'=>'search'),
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
			'layout' => array(self::BELONGS_TO, 'AppearanceLayout', 'appearance_layout_id'),
			'image'  => array(self::BELONGS_TO, 'PlayerRaceAppearanceList', 'player_race_appearance_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'player_id' => 'Player',
			'appearance_layout_id' => 'Appearance Layout',
			'player_race_appearance_id' => 'Player Race Appearance',
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

		$criteria->compare('player_id',$this->player_id);
		$criteria->compare('appearance_layout_id',$this->appearance_layout_id);
		$criteria->compare('player_race_appearance_id',$this->player_race_appearance_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlayerAppearance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @param $playerId integer
	 * @return PlayerAppearance[]
	 */
	public static function getLayouts($playerId){
		$query = "SELECT al.slug, al.layouts, pa.player_race_appearance_id layout_id, pral.id default_layout_id
FROM players p 
LEFT JOIN appearance_layout al ON true
LEFT JOIN player_appearance pa ON pa.appearance_layout_id = al.id AND pa.player_id = p.id
LEFT JOIN player_race_appearance_list pral ON pral.appearance_layout_id = al.id AND pral.default_layout = 1 AND pral.race_id = p.race_id AND pral.gender = p.gender
WHERE p.id = {$playerId}";

		$layers = Yii::app()->db->createCommand($query)->queryAll();
		$ret = [];
		foreach ($layers as $layer){
			$layouts = explode(',',$layer['layouts']);
			$ret[$layer['slug']] = [];
			foreach ($layouts as $layout) {
				$id = $layer['layout_id']?$layer['layout_id']:$layer['default_layout_id'];
				if ($id){
					$model = PlayerRaceAppearanceList::model()->findByPk($id);
					$ret[$layer['slug']][$layout] = $model->getPicture($layout);
				} else {
					$ret[$layer['slug']][$layout] = false;
				}
			}
		}
		return $ret;
	}
}
