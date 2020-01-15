<?php

/**
 * This is the model class for table "{{chat_player}}".
 *
 * The followings are the available columns in table '{{chat_player}}':
 * @property integer $player_id
 * @property string $last_activity
 * @property string $lang_slug
 */
class ChatPlayer extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chat_player_cave}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('lang_slug', 'length', 'max'=>5),
            array('player_id, last_activity', 'required'),
            array('player_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('player_id, last_activity', 'safe', 'on'=>'search'),
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
            'player_id' => 'Player',
            'last_activity' => 'Last Activity',
            'lang_slug' => 'Language'
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
        $criteria->compare('last_activity',$this->last_activity,true);
        $criteria->compare('lang_slug',$this->lang_slug,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChatPlayer the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    /**
     * Получить отсортированный список пользователей чата
     */
    public static function getUsers($lang_slug)
    {
        $players = Yii::app()->db->createCommand()
            ->select('id, user, rank, clan, level, cp.update_chat, cab.image AS avatar')
            ->from('players')
            ->join('{{chat_player_cave}} AS cp', 'cp.player_id = id')
            ->join('{{chat_avatar}} AS ca', 'ca.player_id = cp.player_id')
            ->join('{{chat_avatar_base}} AS cab', 'cab.avatar_id = ca.avatar_id')
            ->andWhere('cp.last_activity >= NOW() - 60')
            ->andWhere("cp.lang_slug = :lang_slug", array(':lang_slug' => $lang_slug))
            ->order('id')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();


        $playerData = array();

        foreach ($players as $player)
        {
            $playerData[] = array(
                'id' => $player->id,
                'user' => $player->user,
                'rankIcon' => Extra::getUserRankIcon($player->rank),
                'clanIcon' => Extra::getClanIcon($player->clan),
                'style' => self::isBan(Ban::getBanTime(Ban::BAN_CHAT, $player)) ? 'banUser' : Extra::getChatCssColorUser($player->rank),
                'updateChat' => $player->update_chat,
                'avatar' => CHtml::encode(self::getAvatar($player->avatar, Ban::getBanTime(Ban::BAN_CHAT, $player)))
            );
        }

        return $playerData;
    }


    /**
     * Забанен ли игрок в чате
     */
    public static function isBan($banTime)
    {
        return $banTime > time() ? date('d-m-Y H:i:s', $banTime) : 0;
    }


    /**
     * Получить аватар
     */
    private static function getAvatar($avatarName, $banTime)
    {
        $avatarChatDir = '/images/chat/avatars/';
        $avatarBannedPic = '/images/chat/banned.png';

        if (self::isBan($banTime))
            return CHtml::image($avatarBannedPic);
        else
            return CHtml::image($avatarChatDir . $avatarName);
    }


    /**
     * В пещерах ли игрок
     */
    public static function isCave($placeUrl)
    {
        $placeArray = array('/caves.php', '/labyrinth.php');

        if (in_array($placeUrl, $placeArray))
            return 1;
        else
            return 0;
    }
}