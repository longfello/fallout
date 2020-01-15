<?php
require_once(YII_PATH . '/../game_tools.php');
require_once(YII_PATH . '/../hack.php');
require_once(YII_PATH . '/../config.php');

/**
 * This is the model class for table "{{combat}}".
 *
 * The followings are the available columns in table '{{combat}}':
 * @property integer $combat_id
 * @property integer $round
 * @property integer $player_1
 * @property integer $player_2
 * @property integer $attacker
 * @property string $time
 * @property integer $is_moved_player_1
 * @property integer $is_moved_player_2
 * @property string $status
 * @property string $win_log
 * @property integer $autopass_player_1
 * @property integer $autopass_player_2
 */
class Combat extends CActiveRecord
{
  const STATUS_ACTIVE = 'active';
  const STATUS_INACTIVE = 'inactive';

  public $round_second_left = 0;
  public $enemy_id = 0;
  protected $__win_log = array();

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{combat}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('autopass_player_1, autopass_player_2', 'required'),
        array('round, player_1, player_2, attacker, is_moved_player_1, is_moved_player_2, autopass_player_1, autopass_player_2', 'numerical', 'integerOnly' => true),
        array('status', 'length', 'max' => 8),
        array('win_log', 'length', 'max' => 1024),
        array('time', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
        array('combat_id, round, player_1, player_2, time, is_moved_player_1, is_moved_player_2, status, win_log, autopass_player_1, autopass_player_2', 'safe', 'on' => 'search'),
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
        'combat_id' => 'Combat',
        'round' => 'Round',
        'player_1' => 'Player 1',
        'player_2' => 'Player 2',
        'attacker' => 'Attacker',
        'time' => 'Time',
        'is_moved_player_1' => 'Is Moved Player 1',
        'is_moved_player_2' => 'Is Moved Player 2',
        'status' => 'Status',
        'win_log' => 'Win Log',
        'autopass_player_1' => 'Autopass Player 1',
        'autopass_player_2' => 'Autopass Player 2',
    );
  }


  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Combat the static model class
   */
  public static function model($className = __CLASS__)
  {
    return parent::model($className);
  }


  public function primaryKey()
  {
    return 'combat_id';
  }


  /**
   * Создать бой
   */
  public static function create($enemyId)
  {
    $enemy = Data::enemy($enemyId);

    $combat = new self;
    $combat->player_1 = Yii::app()->stat->id;
    $combat->player_2 = $enemyId;
    $combat->attacker = Yii::app()->stat->id;
    $combat->time = new CDbExpression('NOW()');

    $combat->autopass_player_1 = Yii::app()->stat->automove;
    $combat->autopass_player_2 = Players::isOnline($enemyId)?$enemy->automove:1;

    $combat->save(false);

    $player = Yii::app()->stat->model;
    /** @var $player Players */
    $player->napad--;
    $player->energy -= min($player->energy, 4);
    $player->pohod += 4;
    $player->save(false);

    $enemy->energy -= min($enemy->energy, 4);
    $enemy->save(false);

    if ($enemy->automove == 0) {
      $cur_lang = t::iso();

      t::getInstance()->setLanguage($enemy->lang_slug, true);
      Players::SendCMD($enemyId, 'popup', array(
        'title' => t::get('На вас напали'),
        'text'  => t::get('Игрок %s напал на вас. Хотите управлять боем или включить автобой?', Yii::app()->stat->user),
        'buttons' => array(
            t::encJs('Управлять')  => 'combatManual',
            t::encJs('Автобой')    => 'combatAutomove'
        )
      ));
      t::getInstance()->setLanguage($cur_lang, true);
    }

    return $combat->combat_id;
  }

  public static function checkAttack(){
    $combat = self::initialization();
    if ($combat){
      $auto = ($combat->player_1 == Yii::app()->stat->id)?$combat->autopass_player_1:$combat->autopass_player_2;
      if (!$auto) {
        $enemy_id = ($combat->player_1 == Yii::app()->stat->id)?$combat->player_2:$combat->player_1;
        $enemy    = Players::model()->findByPk($enemy_id);

        $ccc = new CController('context');
        $ccc->renderInternal( YiiBase::getPathOfAlias('application.modules.combat.views.default').'/popup.php', array(
            'combat' => $combat,
            'enemy'  => $enemy
        ));
      }
    }
  }

  /**
   * Инициализация боя
   * @return Combat
   */
  public static function initialization()
  {
    $criteria = new CDbCriteria();
    $criteria->select = '*, TIMESTAMPDIFF(SECOND, time, NOW()) AS round_second_left, (CASE WHEN player_1 = :player THEN player_2 ELSE player_1 END) as enemy_id';
    $criteria->addCondition('player_1=:player OR player_2=:player');
    $criteria->addCondition('status = :status');
    $criteria->params[':player'] = Yii::app()->stat->id;
    $criteria->params[':status']   = Combat::STATUS_ACTIVE;
    $combat = self::model()->find($criteria);
    return $combat;
  }

  /**
   * Количество секунд, прошедших с начала рунда
   */
  public static function getRoundElapsedSeconds($combatId) {
    $cmd = Yii::app()->db->createCommand();
    $cmd->select('TIMESTAMPDIFF(SECOND, time, NOW()) AS round_second_left');
    $cmd->from('{{combat}}');
    $cmd->where('combat_id=:combat_id', array(':combat_id' => $combatId));

    return $cmd->queryScalar();
  }

  public function isAutomove(Players $player){
    $is_automove = ($player->automove == 1);
    $is_automove = $is_automove || ((($this->player_1 == $player->id )?$this->autopass_player_1:$this->autopass_player_2) == 1);
    return $is_automove;
  }

  /**
   * Активен ли текущий бой
   */
  public static function isActivated($combatId)
  {
    $combat = self::model()->findByPk($combatId);
    if ($combat->status == 'active')
      return 1;
    else {
      return 0;
    }
  }


  /**
   * Получить данную модель
   * @return CActiveRecord
   */
  public static function load()
  {
    return self::model()->findByPk(Yii::app()->combat->combat_id);
  }


  /**
   * Проверка: можно ли атаковать этого игрока
   * @param $enemyId
   * @return string Сообщение об ошибке
   */
  public static function isAvailableAttack($enemyId)
  {
    if (Yii::app()->stat->model->energy < 4) {
      return t::get("Вы не можете атаковать других игроков так как устали.");
    } elseif (self::isNotInCity()) {
      return t::get("Вы не можете атаковать других игроков так как находитесь не в городе.");
    } elseif (Yii::app()->stat->model->unv >= 1) {
      return t::get("Вы не можете ни на кого нападать, т.к. у вас установлена неприкосновенность.");
    } elseif (self::isImunne($enemyId)) {
      return t::get('Вы не можете напасть на этого игрока, т.к. у него установлена неприкосновенность. <br/><br/> Установить ее может каждый желающий в <a href="/implant.php" target="_blank">Лаборатории</a>');
    } elseif (self::isAttackedDuringDay($enemyId)) {
      return t::get("Вы недавно атаковали игрока %s. Атака этого персонажа возможна после 00:00 МСК.", Data::enemy($enemyId)->user);
    } elseif (self::isRunningCombat($enemyId)) {
      return t::get("Вы или Ваш соперник уже находится в бое");
    } elseif (self::isFarAway($enemyId)) {
      return t::get("Ваш соперник находится вне досигаемости");
    } elseif (self::isLevelWrong($enemyId)) {
      return t::get("Уровень вашего соперника не соответствует вашему уровню. Вы можете нападать на противников %s-%s уровня.", array(Yii::app()->stat->level-2, Yii::app()->stat->level + 2));
    } elseif (self::isAlive($enemyId)) {
      return t::get("Игрок мертв. Вы можете нападать только на живых противников.");
    } elseif (self::isAlive(Yii::app()->stat->id)) {
      return t::get("Вы мертвы. Мертвецы не могут нападать на противников.");
    } elseif (self::isNapadScoreAvailable()) {
      return t::get(t::get("На сегодня хватит... <br><br> Использованы все очки нападения в сутки.")).'<br>'.
             t::get("Для получения дополнительных нападений перейдите <a href='/vipivka.php' style='text-decoration:underline;' target='_blank'>в Бар и купите пиво</a>.");
    } elseif (self::isEnemyNitInLabyrinth($enemyId)) {
      $enemy  = Players::model()->findByPk($enemyId);
      return t::get(t::get("Нельзя напасть на персонажа %s, т.к. он находится в недрах Токсических пещер.", $enemy['user']));
    }
    return false;
  }

  public static function isNotInCity(){
    return ((Yii::app()->stat->model->x != 0) || (Yii::app()->stat->model->y != 0));
  }

  public static function isEnemyNitInLabyrinth($enemyId){
    $enemy  = Players::model()->findByPk($enemyId);
    $cave_district = $enemy->getMeta('cave_district', '');
    return ($enemy->labyrinth_x || $enemy->labyrinth_y || ($enemy->travel_place=='/caves.php' && $cave_district!='main'));
  }

  public static function isNapadScoreAvailable(){
    $player = Yii::app()->stat->model;
    return ($player->napad <= 0);
  }

  public static function isImunne($enemyId) {
    $enemy  = Players::model()->findByPk($enemyId);

    if ($enemy) {
      if ($enemy->unv == 0) return false;
    }
    return true;
  }

  public static function isLevelWrong($enemyId) {
    $player = Yii::app()->stat->model;
    $enemy  = Players::model()->findByPk($enemyId);

    if ($player && $enemy) {
      if (($player->level >= $enemy->level-2) && ($player->level <= $enemy->level+2)) return false;
    }
    return true;
  }
  public static function isAlive($enemyId) {
    $enemy  = Players::model()->findByPk($enemyId);

    if ($enemy) {
      if ($enemy->hp > 0) return false;
    }
    return true;
  }
  public static function isFarAway($enemyId) {
    $player = Yii::app()->stat->model;
    $enemy  = Players::model()->findByPk($enemyId);

    if ($player && $enemy) {
      $distance = sqrt($enemy->x*$enemy->x + $enemy->y*$enemy->y);
      if ($distance <= $player->level + 2) return false;
    }
    return true;
  }
  /**
   * Проверка: не воевали ли эти игроки в течении последних суток
   */
  public static function isAttackedDuringDay($enemyId)
  {
    if (Combat::initialization()) {
      Yii::app()->controller->redirect('/combat');
    }

    $cmd = Yii::app()->db->createCommand();
    $cmd->select('CASE WHEN DATE(time) = CURRENT_DATE THEN 1 ELSE 0 END as isToday');
    $cmd->from('{{combat}}');
    $cmd->where('(player_1=:player_2 OR player_2=:player_2) AND attacker=:player_1', array(':player_1' => Yii::app()->stat->id, ':player_2' => $enemyId));
    $cmd->order('time DESC');
    $cmd->limit = 1;

    return ($cmd->queryScalar() == 1);
  }
  /**
   * Проверка: учавствует ли игрок сейчас в бою
   */
  public static function isRunningCombat($enemyId){
    $criteria = new CDbCriteria();
    $criteria->condition = "status=:status AND ((player_1=:enemy_player_id) OR (player_2=:enemy_player_id))";
    $criteria->params = array(':status' => Combat::STATUS_ACTIVE, ':enemy_player_id' => $enemyId);
    $combat = self::model()->find($criteria);
    return $combat;
  }

  /**
   * Установить автоход
   */
  public static function setAutopass(Combat $combatModel)
  {
    //$combat = self::model()->findByPk(Yii::app()->combat->combat_id);
    if ($combatModel->player_1 == Yii::app()->stat->id)
      $combatModel->autopass_player_1 = 1;
    else
      $combatModel->autopass_player_2 = 1;
    $combatModel->save(false);
  }


  /**
   * Отметить в базе, что игрок сделал ход
   */
  public static function makeMove()
  {
    $combat = self::model()->findByPk(Yii::app()->combat->combat_id);

    if ($combat->player_1 == Yii::app()->stat->id) {
      $combat->is_moved_player_1 = 1;
    } else
      $combat->is_moved_player_2 = 1;

    $combat->save(false);
  }


  /**
   * Разрешить ход игрокам
   */
  public static function allowMove()
  {
    $combat = self::model()->findByPk(Yii::app()->combat->combat_id);
    $combat->is_moved_player_1 = 0;
    $combat->is_moved_player_2 = 0;
    $combat->save(false);

    //$_SESSION['combat']['isEnableWay'] = 1;
  }


  /**
   * Получить текущий id боя
   */
  public static function getId()
  {
    return (int)Yii::app()->request->getQuery('id');
  }

  /**
   * @param $playerID
   * @return CombatRound
   */
  public function getRound($_playerID = false){
    $_playerID = $_playerID?$_playerID:Yii::app()->stat->id;

    $criteria = new CDbCriteria();
    $criteria->addCondition("player_id = ".$_playerID);
    $criteria->addCondition("combat_id = ".$this->combat_id);
    $criteria->addCondition("round = ".$this->round);

    $round = CombatRound::model()->find($criteria);
    if (!$round) {
      $round = new CombatRound();
      $round->player_id = $_playerID;
      $round->combat_id = $this->combat_id;
      $round->round     = $this->round;

      $player = Players::model()->findByPk($_playerID);
      $automoveByCombat = ($_playerID == $this->player_1)?$this->autopass_player_1:$this->autopass_player_2;
      if ($player->automove || $automoveByCombat || !Players::isOnline($_playerID)) {
        $round->attack = CombatRoundEnum::correctOrRandom();
        $round->block  = CombatRoundEnum::correctOrRandom();
      }

      $round->save(false);
    }
    return $round;
  }

  /**
   * Оба ли игрока совершили ход?
   * @return bool
   */
  public function isRoundDone(){
    $round_player_1 = $this->getRound($this->player_1);
    $round_player_2 = $this->getRound($this->player_2);
    return ($round_player_1->attack && $round_player_2->attack);
  }

  public function moveComplete(){
    Players::SendCMD($this->enemy_id, 'combatMoveComplete');
  }

  public function calcFirstAction(){
	  /** @var  $p1 Players */
	  $p1 = Players::model()->findByPk($this->player_1);
	  /** @var  $p2 Players */
	  $p2 = Players::model()->findByPk($this->player_2);
	  $p1data = $p1->getOverallStat();
	  $p2data = $p2->getOverallStat();

	  $summ  = $p1data->agility + $p2data->agility;
	  $prox1 = 100 * $p1data->agility / $summ;
	  $prox2 = 100 * $p2data->agility / $summ;

	  $prox1 = rand(0, $prox1);
	  $prox2 = rand(0, $prox2);

//	  echo("{$p1->id} = $prox1, {$p2->id} = $prox2 <br>\n");

	  if ($prox1 > $prox2) {
		  return array($p1, $p2);
	  } else {
		  return array($p2, $p1);
	  }
  }

  public function nextRound(){
	/** @var  $p1 Players */
	$p1 = Players::model()->findByPk($this->player_1);
	/** @var  $p2 Players */
	$p2 = Players::model()->findByPk($this->player_2);

//	echo("{$p1->user} => {$p2->user}<br>");

    if ($p1 && $p2) {
      // Текуший игрок атакует второго

	  list($p1, $p2) = $this->calcFirstAction();
	  $this->player_1          = $p1->id;
	  $this->autopass_player_1 = $p1->automove;
	  $this->player_2          = $p2->id;
  	  $this->autopass_player_2 = $p2->automove;

      $this->attack($p1->id, $p2->id, 1);
      $this->attack($p2->id, $p1->id, 2);

      $this->time = new CDbExpression('NOW()');
      $this->round++;
      if (!$this->save()) { echo CHtml::errorSummary($this); }
      $p1->refresh();
      $p2->refresh();
      $delay1 = rand(1,5);
      $delay2 = 5-$delay1;
      $cur_lang = t::iso();

      if ($p1->automove==0 && $this->autopass_player_1==0) {
        $delay1 = 0;
      }

      if ($p2->automove==0 && $this->autopass_player_2==0) {
        $delay2 = 0;
      }

      t::getInstance()->setLanguage($p1->lang_slug, true);
      $data1 = array(
      	'hp' => array(
	      $p1->id  => $p1->hp,
	      $p2->id  => $p2->hp,
        ),
        'log'   => $this->getLog(),
        'delay' => $delay1
      );

      if ($this->status != Combat::STATUS_ACTIVE) {
        $data1['text'] = ($p1->hp)?t::get("Победа!"):t::get("Поражение!");
      }

      t::getInstance()->setLanguage($p2->lang_slug, true);
      $data2 = array(
	      'hp' => array(
		      $p1->id  => $p1->hp,
		      $p2->id  => $p2->hp,
	      ),
	      'log'   => $this->getLog(),
	      'delay' => $delay1
      );
      if ($this->status != Combat::STATUS_ACTIVE) {
        $data2['text'] = ($p2->hp) ? t::get("Победа!") : t::get("Поражение!");
      }

      t::getInstance()->setLanguage($cur_lang, true);

      if ($this->status == Combat::STATUS_ACTIVE) {
        Players::SendCMD($this->player_1, 'combatNextRound', $data1);
        Players::SendCMD($this->player_2, 'combatNextRound', $data2);
      } else {
        Players::SendCMD($this->player_1, 'combatComplete', $data1);
        Players::SendCMD($this->player_2, 'combatComplete', $data2);
      }
    } else {
      $this->status = Combat::STATUS_INACTIVE;
      $this->save(false);
    }
  }

  public function attack($playerOneId, $playerTwoId, $order){
    if ($this->status == Combat::STATUS_ACTIVE) {
      /** @var  $p1 Players */
      $p1 = Players::model()->findByPk($playerOneId);
      $p1data = $p1->getOverallStat();
      /** @var  $p2 Players */
      $p2 = Players::model()->findByPk($playerTwoId);
      $p2data = $p2->getOverallStat();

      $round_player_1 = $this->getRound($playerOneId);
      $round_player_2 = $this->getRound($playerTwoId);

      $block = ($round_player_1->attack == $round_player_2->block);

      if (!$block) {
        // базовая сила удара
        $att = round($p1data->strength + ($p1data->agility / 6));
        // базовая сила защиты
        $def = round($p2data->defense + ($p2data->agility / 6));
        // критовый шанс
        $crit = $p1data->agility / max(1, $p2data->agility);
        $crit = max(1, round((100 * $crit) / ($crit + 7)));
        // Вариация повреждения
        $att2 = $att + round($att * $p1data->agility / ($p1data->agility + 400));

        // Непосредственно атака
        // Теперь вдарим
        // Дамадж будем рандомизировать...
        $d = mt_rand( max(1, $att-3) , $att2);
        // Для крита дамаж увеличиваем втрое
        if (mt_rand(0, 100) < $crit) {
          $d = round($d * mt_rand(110, 150) / 100);
        }
        $damage = max(0, $d - $def);
        if ($damage == 0) {
          $damage = mt_rand(1, 3);
          if (mt_rand(0, 100) < $crit) {
            $damage = round($damage * mt_rand(150, 300) / 100);
          }
        }

        // Аккумулируем дамадж (только действующий)
        $damage = min($p2->hp, $damage);
      } else {
        $damage = 1;
      }

      $p1->energy--;
      $p1->save(false);
      $p1->refresh();

      $phrase = false;
      $p2->hp -= $damage;

      if ($p2->energy <= 0) {
        $p2->hp = 0;
        $p2->energy = 0;
        $phrase = new combatlog_phrase(combatlog_phrase::EVENT_NO_ENERGY, array(
          'event' => combatlog_phrase::EVENT_NO_ENERGY,
          'looser_id' => $p2->id,
          'winner_id' => $p1->id
        ));
//          $p2->getLinkAndName().' '.t::get("не в силах больше сражаться и был жестоко наказан за это").' '.$p1->getLinkAndName()
//        );
      }

      // Запишем в базу
      //$player = Players::model()->findByPk($p2->id);
      //$player->hp = $p2->hp;

      if (!$p2->save(false)) trigger_error(print_r($p2->getErrors(), true));
      $p2->refresh();


      // Если игрок победил
      if ($p2->hp <= 0){
        $gold = RBattle::getGoldForWin($p2);
        $exp  = RBattle::getExpForWin($p2);
        $combatUrl = '/combat/view/'.$this->combat_id;

        //$combat = Combat::model()->findByPk(Yii::app()->combat->combat_id);
        $this->status = Combat::STATUS_INACTIVE;

        /** @var $attacker Players */
        /** @var $defender Players */
        $attacker = Players::model()->findByPk($this->player_1);
        $defender = Players::model()->findByPk($this->player_2);
        $type   = ($p1->id == $attacker->id)?CombatPhrasesTypeEnum::WIN:CombatPhrasesTypeEnum::LOOSE;
        $event  = ($p1->id == $attacker->id)?combatlog_phrase::EVENT_WIN:combatlog_phrase::EVENT_LOOSE;
        $gender = ($attacker->gender == Players::GENDER_MALE)?CombatPhrasesGenderEnum::MALE:CombatPhrasesGenderEnum::FEMALE;

        $phrase = $phrase?$phrase:new combatlog_phrase($event, array(
          'phrase_id' => CombatPhrases::getPhrase($type, $gender)->id,
          'attacker_id' => $attacker->id,
          'defender_id' => $defender->id
        ));

          
          // $phrase?$phrase:$attacker->getLinkAndName().' '.CombatPhrases::getPhrase($type, $gender).' '.$defender->getLinkAndName();

        $clans_status = check_war_or_union($p1->clan, $p2->clan);
        if ($clans_status['war']){
          $clan_exp = round($exp * 0.15);
          $exp     += $clan_exp;
          $this->addWinLog(combatlog_winlog_winclan::add($p1->id, $exp, $clan_exp, $gold));
          // $this->win_log  = "<div class='winner'><span class='info'></span><span>".t::get('Победил боец %s', $p1->getLinkAndName())."!</span></div><br />".t::get('%s получает %s опыта, из которого <span class="battle_win_text">+15%% (%s опыта) за победу над врагом клана</span> и %s <img alt="Золото" src="/images/gold.png"><br>', array($p1->getLinkAndName(), $exp, $clan_exp, $gold));
          _mysql_exec("UPDATE `clans` SET `war_win` = `war_win` + 1 WHERE `id` = {$p1->clan}");
          _mysql_exec("INSERT INTO `clan_war_history` (`clan_id`, `point`, `date`) VALUES ({$p1->clan}, 1, NOW()), ({$p2->clan}, -1, NOW())");
        } else {
          if ($clans_status['union']) {
            _mysql_exec("UPDATE `clans` SET `union_win` = `union_win` + 1 WHERE `id` = {$p1->clan}");
          }
          
          $this->addWinLog(combatlog_winlog_winsingle::add($phrase, $p1->id, $exp, $gold));
          // $this->win_log = "<div class='winner'><span class='info'></span><span>".$phrase->render()."!</span></div><br />".t::get('%s получает %s опыта и %s <img alt="Золото" src="/images/gold.png"><br>', array($p1->getLinkAndName(), $exp, $gold));
        }

        $pets_log = '';
        $pets = Pets::model()->alive()->findAllByAttributes(array('owner' => $round_player_1->player_id));
        if ($pets) {
          $exp     = round($exp * 0.8);
          foreach($pets as $pet){
            /** @var $pet Pets */
            $exp_pet = round($exp / 4 / count($pets));
            $pet->addExperience($exp_pet);
            /*
            $pets_log .= t::get("Его любимый питомец <b>%s</b> получает <b>%s</b> опыта", array($pet->name, $exp_pet));
            if ($pet->isLevelUp){
              $pets_log .= t::get(" и поднялся на новый уровень");
            }
            $pets_log .= '.<br>';
            */
            $this->addWinLog(combatlog_winlog_winpet::add($pet->name, $exp_pet, $pet->isLevelUp));
          }
        }
        //$this->win_log .= $pets_log;
        logdata_you_win_player::add($p1->id, $combatUrl, $p2->id, $p2->user, false, $exp, $gold);
        // RLog::add(t::get('Вы <a href="%s">победили</a> персонажа <b>%s</b>. Получено <b>%s</b> опыта и <b>%s</b> <img alt="Золото" src="/images/gold.png"> золота', array($combatUrl, $p2->getLinkAndName(), $exp, $gold)), $p1->id);
        logdata_you_loose_player::add($p2->id, $combatUrl, $p1->id, $p1->user, $gold);
        // RLog::add(t::get("Вы <a href='%s'>проиграли</a> бой персонажу <b>%s</b>. Потеряно <b>%s</b> <img alt='Золото' src='/images/gold.png'> золота", array($combatUrl, $p1->getLinkAndName(), $gold)), $p2->id);

        game_write_chat("", "<a target=\"blank\" href=\"$combatUrl\">".$phrase->render()."</a> ", null , "!");

        Players::setLastKilled($p1->id, $p2->id, $p2->user, Players::KILLER_PLAYER);
        $p1->wins++;
        $p1->gold += $gold;
        $p1->addExperience($exp);
        if (!$p1->save(false)) trigger_error(print_r($p1->getErrors(), true));

        Players::setLastKilledBy($p2->id, $p1->id, $p1->user, Players::KILLER_PLAYER);
        $p2->gold -= $gold;
        $p2->losses++;

        if (!$p2->save(false)) trigger_error(print_r($p2->getErrors(), true));
      }

      $log_message = $this->getLogMessage($p1, $p2, $block, $damage, $round_player_1);
      $round_player_1->order = $order;
      $round_player_1->save(false);
      $round_player_1->saveLog($log_message);
    }
  }

  public function getLogMessage(Players $p1, Players $p2, $block, $damage, $round){
    if ($block) {
      $phrase = new combatlog_phrase(combatlog_phrase::EVENT_BLOCK, array(
        'attacker_id' => $p1->id,
        'defender_id' => $p2->id,
        'hp'          => $p2->hp
      ));
//      $where_name = CombatRoundEnum::getName($round->attack);
//      return t::get('%s ударил %s. %s поставил блок. Урон: 1. (Осталось: %s)', array($p1->getLinkAndName(), $where_name, $p2->getLinkAndName(), $p2->hp));
    } else {
      $gender = ($p1->gender == Players::GENDER_MALE)?CombatPhrasesGenderEnum::MALE:CombatPhrasesGenderEnum::FEMALE;
      
      $phrase = new combatlog_phrase(combatlog_phrase::EVENT_KICK, array(
        'phrase_id' => CombatPhrases::getPhrase(CombatPhrasesTypeEnum::BATTLE, $gender)->id,
        'attacker_id' => $p1->id,
        'defender_id' => $p2->id,
        'damage'      => $damage,
        'hp'          => $p2->hp
      ));
/*      
      $phrase = CombatPhrases::getPhrase(CombatPhrasesTypeEnum::BATTLE, $gender);
      $phrase = $p1->getLinkAndName().' '.$phrase.' '.$p2->getLinkAndName();

      return $phrase.'. ' . t::get('Урон: %s. (Осталось: %s)', array($damage, $p2->hp));
*/
    }
    return $phrase;
  }

  public function getLog(){
    $criteria = new CDbCriteria();
    $criteria->addCondition('combat_id=:combat_id');
//    $criteria->addCondition('log IS NOT NULL');
    $criteria->params = array(':combat_id' => $this->combat_id);
    $criteria->order = 'round DESC, `order`'; //, player_id = '.$this->player_1.' DESC';
    $rounds = CombatRound::model()->findAll($criteria);

    $logs = array();

    if ($this->status == Combat::STATUS_INACTIVE) {
      $logs['total'] = array($this->renderWinLog());
    }

    foreach($rounds as $round){
      if (!isset($logs[$round->round])) {
        $logs[$round->round] = array();
        $logs[$round->round][] = t::get("<h3>Раунд %s:</h3>", $round->round);
      }
      $logs[$round->round][] = $round->getLog();
    }
    return $logs;
  }

  public function setAutomove($true = true){
    if (Yii::app()->stat->id == $this->player_1) {
      $this->autopass_player_1 = $true?1:0;
    } else {
      $this->autopass_player_2 = $true?1:0;
    }
    $this->time = new CDbExpression('NOW()');
    $this->save();
  }
  
  public function addWinLog($log){
    $this->__win_log[] = $log;
    $this->saveWinLog();
  }

  public function saveWinLog(){
    $this->win_log = CJavaScript::jsonEncode($this->__win_log);
  }

  public function renderWinLog(){
    if (substr($this->win_log, 0, 2) == '[{') {
      $datas = CJavaScript::jsonDecode($this->win_log);
      $text  = '';
      foreach($datas as $data){
        $text .= combatlog_renderer::render($data);
      }
    } else $text = $this->win_log;
    return $text;
  }
}
