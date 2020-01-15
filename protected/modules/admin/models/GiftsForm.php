<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 *
 */
class GiftsForm extends CFormModel
{
  const SEND_TYPE_ALL      = 'all';
  const SEND_TYPE_BY_ID    = 'id';
  const SEND_TYPE_LOGIN    = 'login';
  const SEND_TYPE_LEVEL    = 'level';

  public $equipment_id;
  public $available_types  = array(
      self::SEND_TYPE_ALL      => 'Всем игрокам',
      self::SEND_TYPE_BY_ID    => 'По ID',
      self::SEND_TYPE_LOGIN    => 'По дате входа в игру',
      self::SEND_TYPE_LEVEL    => 'По уровню',
  );

  public $type         = self::SEND_TYPE_ALL;
  public $text;
  public $text_en;
  public $napad = 0;
  public $pohod = 0;
  public $pleft= 0;

  public $item = array();
  public $count = array();

  public $id_from      = 0;
  public $id_to        = 0;
  public $level_from   = 0;
  public $level_to     = 0;
  public $start        = '';
  public $end          = '';

  public function init(){
    parent::init();
  }

  /**
   * Declares the validation rules.
   * The rules state that username and password are required,
   * and password needs to be authenticated.
   */
  public function rules()
  {
    return array(
        array('type, text, text_en, type', 'required'),
        array('start, end', 'length', 'max' => 64),
        array('item, count','type','type'=>'array','allowEmpty'=>false),
        array('equipment_id, id_from, id_to, level_from, level_to, napad, pohod, pleft', 'numerical', 'integerOnly'=>true),
        array('equipment_id, id_from, id_to, level_from, level_to, start, end, napad, pohod, pleft', 'safe'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels()
  {
    return array(
      'equipment_id' => 'Предмет',
      'type' => 'Тип выдачи',
      'text' => 'Текст в лог (ru)',
      'text_en' => 'Текст в лог  (en)',
      'id_from' => 'C',
      'id_to'   => 'По',
      'level_from' => 'C',
      'level_to'   => 'По',
      'start'   => 'С',
      'end'     => 'По',
      'napad' => 'Добавить нападений',
      'pohod' => 'Добавить походных очков',
      'pleft' => 'Добавить дней стимулятора'
    );
  }

  public function getIds(){
    $criteria = new CDbCriteria();
    switch($this->type) {
      case self::SEND_TYPE_ALL:
        break;
      case self::SEND_TYPE_BY_ID:
        $criteria->addBetweenCondition('id', $this->id_from, $this->id_to);
        break;
      case self::SEND_TYPE_LOGIN:
        $criteria->addBetweenCondition('lpv', strtotime($this->start), strtotime($this->end));
        break;
      case self::SEND_TYPE_LEVEL:
        $criteria->addBetweenCondition('level', $this->level_from, $this->level_to);
        break;
    }
    $criteria->select = 'id';

    $command = Yii::app()->db->commandBuilder->createFindCommand('players', $criteria);
    $ids = $command->queryColumn();
    return $ids;
  }

    public function getAttribute($attribute){
        if (isset($this->{$attribute})){
            return $this->{$attribute};
        }
        return null;
    }

    public function tableName(){
        return 'GiftsForm';
    }

}
