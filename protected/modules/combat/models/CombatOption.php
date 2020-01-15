<?php

/**
 * This is the model class for table "{{combat_option}}".
 *
 * The followings are the available columns in table '{{combat_option}}':
 * @property integer $combat_id
 * @property integer $player_id
 * @property integer $refresh
 * @property integer $automove
 */
class CombatOption extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{combat_option}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('combat_id, player_id, refresh, automove', 'required'),
            array('combat_id, player_id, refresh, automove', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('combat_id, player_id, refresh, automove', 'safe', 'on'=>'search'),
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
            'combat_id' => 'Combat',
            'player_id' => 'Player',
            'refresh' => 'Refresh',
            'automove' => 'Automove',
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

        $criteria->compare('combat_id',$this->combat_id);
        $criteria->compare('player_id',$this->player_id);
        $criteria->compare('refresh',$this->refresh);
        $criteria->compare('automove',$this->automove);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CombatOption the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function primaryKey()
    {
        return array('combat_id', 'player_id');
    }


    /**
     * Выставить параметр на обновление страницы
     */
    public static function setRefresh()
    {
        self::model()->updateAll(array('refresh' => 1), "combat_id=" . Yii::app()->combat->combat_id);
    }


    /**
     * Можно ли обновлять страницу
     */
    public static function isRefresh($combatId)
    {
        $model = self::model()->findByPk(array('combat_id' => $combatId, 'player_id' => Yii::app()->stat->id));
        if ($model->refresh)
        {
            $model->refresh = 0;
            $model->save(false);
            return 1;
        }

        return 0;
    }


    /**
     * Установлен ли автоход у текущего игрока
     */
    public static function getPlayerAutoMove($combatId)
    {
        $model = self::model()->findByPk(array('combat_id' => $combatId, 'player_id' => Yii::app()->stat->id));

        return $model->automove;
    }
    
    
    /**
     * Установить автоход игроку
     */
    public static function setAutoMove($isTrue = true)
    {
      $combat = Combat::initialization();
      $model = self::model()->findByPk(array('combat_id' => $combat->combat_id, 'player_id' => Yii::app()->stat->id));
      $model->automove = $isTrue?1:0;
      $model->save(false);

      Players::SendCMD($combat->enemy_id, 'combatAutomove', array('state' => $isTrue));
    }


    /**
     * Проверить: установлен ли автоход у соперника
     */
    public static function getEnemyAutoMove()
    {
      $combat = Combat::initialization();
      $model = self::model()->findByPk(array('combat_id' => $combat->combat_id, 'player_id' => $combat->enemy_id));
      return $model->automove;
    }
}