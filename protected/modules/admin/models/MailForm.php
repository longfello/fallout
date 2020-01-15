<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class MailForm extends CFormModel
{
  const SEND_TYPE_ALL      = 'all';
  const SEND_TYPE_BY_ID    = 'id';
  const SEND_TYPE_LOGIN    = 'login';
  const SEND_TYPE_LEVEL    = 'level';
  const SEND_TYPE_SELECTED = 'selected';
  const SEND_TYPE_EXCLUDED = 'excluded';

  public $available_types  = array(
      self::SEND_TYPE_ALL      => 'Всем игрокам',
      self::SEND_TYPE_BY_ID    => 'По ID',
      self::SEND_TYPE_LOGIN    => 'По дате входа в игру',
      self::SEND_TYPE_LEVEL    => 'По уровню',
      self::SEND_TYPE_SELECTED => 'Выбранным игрокам',
      self::SEND_TYPE_EXCLUDED => 'Игрокам, исключая',
  );

  public $sender;
  public $sender_en;
  public $type         = self::SEND_TYPE_ALL;
  public $title;
  public $title_en;
  public $content;
  public $content_en;

  public $selected_ids = array();
  public $excluded_ids = array();
  public $sender_id    = 0;
  public $id_from      = 0;
  public $id_to        = 0;
  public $level_from   = 0;
  public $level_to     = 0;
  public $start        = '';
  public $end          = '';
  public $send_type    = '';

  public function init(){
    $this->sender = Yii::app()->stat->model->user;
    $this->sender_en = Yii::app()->stat->model->user;
//    $this->start  = date('Y-m-d');
//    $this->end    = date('Y-m-d');
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
        array('type, title, title_en, content, content_en, sender, sender_en,  send_type', 'required'),
        array('start, end', 'length', 'max' => 64),
        array('id_from, id_to, level_from, level_to, sender_id', 'numerical', 'integerOnly'=>true),
        array('selected_ids, excluded_ids','type','type'=>'array','allowEmpty'=>false),
        array('selected_ids, excluded_ids, id_from, id_to, level_from, level_to, start, end', 'safe'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels()
  {
    return array(
      'sender' => 'Отправитель (ru)',
      'sender_en' => 'Отправитель (en)',
      'sender_id' => 'ID отправителя',
      'type' => 'Тип рассылки',
      'title' => 'Заголовок (ru)',
      'title_en' => 'Заголовок (en)',
      'content' => 'Текст письма (ru)',
      'content_en' => 'Текст письма (en)',
      'id_from' => 'C',
      'id_to'   => 'По',
      'level_from' => 'C',
      'level_to'   => 'По',
      'start'   => 'С',
      'end'     => 'По',
      'send_type' => 'Вариант отправки'
    );
  }

  public function getIds(){
    $criteria = new CDbCriteria();
    if ($this->send_type=='email') {
    	$criteria->addCondition("nomail = 0");
    	$criteria->addCondition("email_confirmed = '". Players::YES ."'");
    }
    switch($this->type) {
      case self::SEND_TYPE_ALL:
        break;
      case self::SEND_TYPE_BY_ID:
        $criteria->addBetweenCondition('id', $this->id_from, $this->id_to);
        break;
      case self::SEND_TYPE_LOGIN:
       //echo($this->start. '-' .$this->end.'<br>');
       //echo(strtotime($this->start). '-' .strtotime($this->end).'<br>');
        $criteria->addBetweenCondition('lpv', strtotime($this->start), strtotime($this->end));
        break;
      case self::SEND_TYPE_LEVEL:
        $criteria->addBetweenCondition('level', $this->level_from, $this->level_to);
        break;
      case self::SEND_TYPE_EXCLUDED:
        $criteria->addNotInCondition('id', $this->excluded_ids);
        break;
      case self::SEND_TYPE_SELECTED:
        $criteria->addInCondition('id', $this->selected_ids);
        break;
    }
    $criteria->select = 'id';

    $command = Yii::app()->db->commandBuilder->createFindCommand('players', $criteria);
    $ids = $command->queryColumn();
    return $ids;
  }

}
