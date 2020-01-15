<?php

/**
 * This is the model class for table "{{combat_round}}".
 *
 * The followings are the available columns in table '{{combat_round}}':
 * @property integer $round_id
 * @property integer $combat_id
 * @property integer $player_id
 * @property integer $round
 * @property string $attack
 * @property string $block
 * @property string $log
 * @property integer $order
 */
class CombatRound extends CActiveRecord
{
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{combat_round}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('combat_id, player_id, round', 'required'),
        array('combat_id, player_id, round, order', 'numerical', 'integerOnly' => true),
          array('block, attack','in', 'range' => CombatRoundEnum::$available),
        array('log', 'length', 'max' => 255),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
        array('round_id, combat_id, player_id, round, attack, block, log', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array();
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
        'round_id' => 'Round',
        'combat_id' => 'Combat',
        'player_id' => 'Player',
        'round' => 'Round',
        'attack' => 'Атака',
        'block' => 'Блок',
        'log' => 'Log',
        'order' => 'Очередность удара',
    );
  }


  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return CombatRound the static model class
   */
  public static function model($className = __CLASS__)
  {
    return parent::model($className);
  }

  public function primaryKey()
  {
    return 'round_id';
  }


  /**
   * Доступен ли ход текущему игроку
   */
  public function isEnabledMove()
  {
    $combatRoundModel = self::model()->find(
        'combat_id=:combat_id AND player_id=:player_id AND round=:round',
        array(':combat_id' => $this->combat_id, ':player_id' => Yii::app()->stat->id, 'round' => $this->round)
    );
    return (!$combatRoundModel);
  }


  /**
   * Получить все раунды боя
   */
  public static function getAll($combatId)
  {
    $criteria = new CDbCriteria();
    $criteria->condition = 'combat_id=:combat_id';
    $criteria->params = array(':combat_id' => $combatId);
    $criteria->order = 'round DESC';
    $combatRounds = CombatRound::model()->findAll($criteria);

    return $combatRounds;
  }


  /**
   * Проверить: сделали ход оба игрока в этом раунде
   */
  public static function isBothPlayersMoved()
  {
    $combat = Combat::initialization();

    $criteria = new CDbCriteria();
    $criteria->condition = 'combat_id=' . $combat->combat_id;
    $criteria->addCondition('round=' . $combat->round);

    $countRound = self::model()->count($criteria);

    return ($countRound > 1);
  }


  /**
   * Проверить: походил ли враг
   */
  public static function isEnemyMoved()
  {
    $combat = Combat::initialization();
    $criteria = new CDbCriteria();
    $criteria->condition = 'combat_id=' . $combat->combat_id;
    $criteria->addCondition('round=' . $_SESSION['combat']['round']);
    $criteria->addCondition('player_id=' . $combat->enemy_id);

    return self::model()->count($criteria);
  }


  /**
   * Добавить сообщение лога
   */
  public static function addLogMessage($playerId, $message, $round)
  {
    $combat = Combat::initialization();
    $criteria = new CDbCriteria();
    $criteria->condition = 'combat_id=' . $combat->combat_id;
    $criteria->addCondition('round=' . $round);
    $criteria->addCondition('player_id=' . $playerId);
    $combatRound = self::model()->find($criteria);

    $combatRound->log = $message;
    $combatRound->save(false);
  }


  /**
   * Получить информацию о том, смогут ли атаковать игроки
   */
  public static function getAttackData($round)
  {
    $combat = Combat::initialization();

    $currentPlayer = self::getPlayerRoundData(Yii::app()->stat->id, $round);
    $anotherPlayer = self::getPlayerRoundData($combat->enemy_id, $round);

    $selectedCurrentPlayer = array_keys($currentPlayer->attributes, 1);
    $selectedAnotherPlayer = array_keys($anotherPlayer->attributes, 1);

    $current = self::isAttack($selectedCurrentPlayer, $selectedAnotherPlayer);
    $another = self::isAttack($selectedAnotherPlayer, $selectedCurrentPlayer);

    return array('playerOne' => $current, 'playerTwo' => $another);
  }


  /**
   * Получить информацию о раунде текущего игрока
   */
  public static function getPlayerRoundData($playerId, $round)
  {
    $combat = Combat::initialization();

    $criteria = new CDbCriteria();
    $criteria->select = 'attack_head, attack_hand, attack_body, attack_foot, block_head, block_hand, block_body, block_foot';
    $criteria->condition = 'combat_id=' . $combat->combat_id;
    $criteria->addCondition('round=' . $round);
    $criteria->addCondition('player_id=' . $playerId);
    return self::model()->find($criteria);
  }


  /**
   * Проверка: сможет ли игрок атаковать другого
   * @param array $firstPlayerParams Параметры первого игрока
   * @param array $twoPlayerParams Параметры второго игрока
   * @return boolean Может ли первый игрок атаковать
   */
  public static function isAttack($firstPlayerParams, $twoPlayerParams)
  {
    $isAttack = true;

    switch ($firstPlayerParams[0]) {
      case 'attack_head':
        if ($twoPlayerParams[1] == 'block_head') {
          $isAttack = false;
        }
        break;
      case 'attack_hand':
        if ($twoPlayerParams[1] == 'block_hand') {
          $isAttack = false;
        }
        break;
      case 'attack_body':
        if ($twoPlayerParams[1] == 'block_body') {
          $isAttack = false;
        }
        break;
      case 'attack_foot':
        if ($twoPlayerParams[1] == 'block_foot') {
          $isAttack = false;
        }
        break;
    }

    return $isAttack;
  }


  /**
   * Получить лог удара врага
   */
  public static function getEnemyLog($round)
  {
    $combat = Combat::initialization();

    $criteria = new CDbCriteria();
    $criteria->select = 'log';
    $criteria->condition = 'combat_id=' . $combat->combat_id;
    $criteria->addCondition('round=' . $round);
    $criteria->addCondition('player_id=' . $combat->enemy_id);

    $model = self::model()->find($criteria);
    return $model->getLog(); //$model->log;
  }


  /**
   * Получить лог последнего раунда
   */
  public static function getLastRoundLog()
  {
    $combat = Combat::initialization();

    $criteria = new CDbCriteria();
    $criteria->select = 'log';
    $criteria->condition = 'combat_id=' . $combat->combat_id;
    $criteria->addCondition('round=' . ($_SESSION['combat']['round'] - 1));
    $criteria->addInCondition('player_id', array(Yii::app()->stat->id, $combat->enemy_id));

    return self::model()->findAll($criteria);
  }

  /**
   * Выставить случайные значения атаки защиты
   */
  public static function getRandomValues($attributes)
  {
    $attack = mt_rand(0, 3);
    $block = mt_rand(4, 7);

    $newAttributes = array();
    $i = 0;
    foreach ($attributes as $key => $value) {
      $newAttributes[$key] = 0; // Сброс значения
      if ($i == $attack || $i == $block) {
        $newAttributes[$key] = 1;
      } else {
        $newAttributes[$key] = 0;
      }
      $i++;
    }

    return $newAttributes;
  }


  /**
   * Сгруппировать раунды
   */
  public static function groupRounds($rounds)
  {
    $groupedRounds = array();
    //echo '<pre>' . print_r($rounds, 1) . '</pre>';
    foreach ($rounds as $round) {
      $groupedRounds[$round->round][] = $round->getLog(); //$round->log;
    }

    //echo '<pre>' . print_r($groupedRounds, 1) . '</pre>';

    return $groupedRounds;
  }

  public function setParam($attack, $block){
    $this->attack = CombatRoundEnum::correctOrRandom($attack);
    $this->block  = CombatRoundEnum::correctOrRandom($block);
    $this->save();
  }
  
  public function saveLog(combatlog_phrase $phrase){
    $phrase->save($this->combat_id, $this->round_id);
  }

  public function getLog(){
    $model = CombatLog::model()->findByAttributes(array('battle_id'=>$this->combat_id, 'round_id'=>$this->round_id));

    if ($model) {
      return combatlog_renderer::render($model->data);
    } else return '';
  }
}
