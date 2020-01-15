<?php

/**
 * This is the model class for table "banned_players".
 *
 * The followings are the available columns in table 'banned_players':
 * @property string $id
 * @property integer $player_id
 * @property string $login
 * @property integer $block_ip
 * @property integer $chat
 * @property integer $site
 * @property integer $advert
 * @property string $until
 * @property string $comment
 * @property string $reason
 * @property integer $admin_id
 * @property string $created
 * @property string $active_chat
 * @property string $active_site
 * @property string $active_advert
 */
class BannedPlayers extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'banned_players';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, block_ip, chat, site, advert, active_chat, active_site, active_advert, admin_id', 'numerical', 'integerOnly'=>true),
			array('login', 'length', 'max'=>15),
			array('reason', 'length', 'max'=>255),
			array('until, comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, player_id, login, block_ip, chat, site, advert, until, comment, reason, created, active_chat, active_site, active_advert', 'safe', 'on'=>'search'),
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
			'ips' => array(self::HAS_MANY, 'BannedIp', array('ban_id' => 'id')),
			'admin'=>array(self::HAS_ONE, 'User', array('id'=>'admin_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'player_id' => 'Игрок',
			'login' => 'Имя пользователя',
			'block_ip' => 'Блокировать IP адрес',
			'chat' => 'Блокировать чат',
			'site' => 'Блокировать сайт',
			'advert' => 'Блокировать объявления',
			'until' => 'Блокировать до (пусто - бесконечно)',
			'reason' => 'Причина блокировки',
			'comment' => 'Комментарий к правилу блокировки',
			'admin_id' => 'Админ',
			'created' => 'Дата создания',
			'active_chat' => 'Амнистия чата',
			'active_site' => 'Амнистия сайта',
			'active_advert' => 'Амнистия рекламы',
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
		$criteria->compare('player_id',$this->player_id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('block_ip',$this->block_ip);
		$criteria->compare('chat',$this->chat);
		$criteria->compare('site',$this->site);
		$criteria->compare('advert',$this->advert);
		$criteria->compare('until',$this->until,true);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('active_chat',$this->active_chat);
		$criteria->compare('active_site',$this->active_site);
		$criteria->compare('active_advert',$this->active_advert);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BannedPlayers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave(){
    if (!strtotime($this->until)) {
      $this->until = null;
    }
    return parent::beforeSave();
  }
	public function afterSave(){
		if ($this->block_ip) {
			$player = Players::model()->findByPk($this->player_id);
			if ($player){
				if (!BannedIp::model()->findByAttributes(array(
          'ban_id' => $this->id,
          'ip'     => ip2long($player->ip)
        ))) {
          $model = new BannedIp();
          $model->ban_id = $this->id;
          $model->ip     = ip2long($player->ip);
          $model->save();
        }
			}
		} else {
      BannedIp::model()->deleteAllByAttributes(array('ban_id' => $this->id));
    }
	}

  public function afterDelete() {
    BannedIp::model()->deleteAllByAttributes(array('ban_id' => $this->id));
    parent::afterDelete(); // TODO: Change the autogenerated stub
  }
}
