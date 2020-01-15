<?php

/**
 * This is the model class for table "players".
 *
 * The followings are the available columns in table 'players':
 * @property integer $id
 * @property string $user
 * @property string $email
 * @property string $pass
 * @property string $pass2
 * @property string $question
 * @property string $answer
 * @property string $rank
 * @property integer $level
 * @property integer $gold
 * @property integer $exp
 * @property integer $energy
 * @property integer $max_energy
 * @property integer $strength
 * @property integer $agility
 * @property integer $hp
 * @property integer $max_hp
 * @property integer $bank
 * @property integer $ap
 * @property integer $wins
 * @property integer $losses
 * @property string $tag
 * @property integer $platinum
 * @property integer $age
 * @property string $gender
 * @property string $location
 * @property string $profile
 * @property string $msn
 * @property integer $logins
 * @property string $lpv
 * @property string $page
 * @property string $ip
 * @property integer $clan
 * @property string $ClanThresholdTime
 * @property string $PreviousClanId
 * @property string $clan_money
 * @property string $clan_store
 * @property integer $refs
 * @property integer $mines
 * @property integer $ops
 * @property integer $alethite
 * @property integer $burelia
 * @property string $email_confirmed
 * @property integer $defense
 * @property integer $opit
 * @property integer $napad
 * @property integer $ewins
 * @property integer $pohod
 * @property string $q_gauss
 * @property integer $nablud
 * @property integer $pleft
 * @property integer $impl
 * @property integer $hidde_in_records
 * @property string $chattime
 * @property string $radiotime
 * @property string $chatclantime
 * @property string $chattimecaves
 * @property string $logme
 * @property string $etm
 * @property string $rq_ts_d
 * @property integer $rq_id_d
 * @property integer $rq_ts_h
 * @property integer $rq_id_h
 * @property string $rq_ok
 * @property integer $rq_st
 * @property integer $rq_drop1_st
 * @property integer $rq_drop2_st
 * @property integer $rq_cnt
 * @property string $rq_from
 * @property integer $distance
 * @property integer $x
 * @property integer $y
 * @property integer $buy_submob_info
 * @property integer $last_wrong_login_cnt
 * @property string $last_wrong_login_time
 * @property integer $mail_limit
 * @property integer $read_news
 * @property integer $buy_map_pustosh
 * @property integer $ref
 * @property integer $unv
 * @property integer $reg_date
 * @property integer $wins_quest
 * @property integer $vk_id
 * @property string $unv_start
 * @property integer $unv_ban
 * @property string $last_visit_toxic_caves
 * @property integer $nomail
 * @property string $token
 * @property string $travel_place
 * @property integer $labyrinth_y
 * @property integer $labyrinth_x
 * @property integer $automove
 * @property string $provider_uid
 * @property string $provider
 * @property string $lang_slug
 * @property int $race_id
 *
 * @property Pets $pet
 * @property PlayerRace $race
 * @property Appearance $appearance
 * @property Outposts $outpost
 */
class Players extends CActiveRecord
{
  public $isLevelUp = false;
  public $ap_gain   = 0;
  public $banChat;
  public $banSite;
  public $banAdvert;
  public $remember;

  private $overallData;

  const GENDER_MALE = 'male';
  const GENDER_FEMALE = 'female';

  const KILLER_PLAYER  = 'players';
  const KILLER_MONSTER = 'npc';

  const REMEMBER_COOKIE = 'user_token';

  const NO = 'No';
  const YES = 'Yes';

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'players';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('user, pass2, PreviousClanId, chattimecaves, mail_limit, read_news, ref, unv, vk_id, travel_place, labyrinth_y, labyrinth_x', 'required'),
        array('level, gold, exp, energy, max_energy, strength, agility, hp, max_hp, bank, ap, wins, losses, platinum, age, logins, clan, refs, mines, ops, alethite, burelia, defense, opit, napad, ewins, pohod, nablud, pleft, impl, hidde_in_records, rq_id_d, rq_ts_h, rq_id_h, rq_st, rq_drop1_st, rq_drop2_st, rq_cnt, distance, x, y, buy_submob_info, last_wrong_login_cnt, mail_limit, read_news, buy_map_pustosh, ref, unv, reg_date, wins_quest, vk_id, unv_ban, nomail, labyrinth_y, labyrinth_x, race_id', 'numerical', 'integerOnly' => true),
        array('automove', 'numerical', 'integerOnly' => true),
        array('user, tag, location', 'length', 'max' => 15),
        array('provider_uid, provider', 'length', 'max' => 255),
        array('email, pass, pass2', 'length', 'max' => 60),
        array('question, answer', 'length', 'max' => 64),
        array('rank, PreviousClanId', 'length', 'max' => 10),
        array('msn', 'length', 'max' => 30),
        array('gender', 'length', 'max' => 11),
        array('profile', 'length', 'max' => 1500),
        array('lang_slug', 'length', 'max' => 5),
        array('lpv, chattime, radiotime, chatclantime, chattimecaves, etm, rq_ts_d', 'length', 'max' => 20),
        array('page', 'length', 'max' => 100),
        array('ip', 'length', 'max' => 50),
        array('clan_money, clan_store, q_gauss, logme, rq_ok', 'length', 'max' => 1),
        array('email_confirmed', 'length', 'max' => 3),
        array('rq_from', 'length', 'max' => 4),
        array('token, travel_place', 'length', 'max' => 255),
        array('ClanThresholdTime, last_wrong_login_time, unv_start, last_visit_toxic_caves', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
        array('id, remember, banChat, banSite, banAdvert, user, email, pass, pass2, question, answer, rank, level, gold, exp, energy, max_energy, strength, agility, hp, max_hp, bank, ap, wins, losses, tag, platinum, age, gender, location, profile, msn, logins, lpv, page, ip, clan, ClanThresholdTime, PreviousClanId, clan_money, clan_store, refs, mines, ops, alethite, burelia, email_confirmed, defense, opit, napad, ewins, pohod, q_gauss, nablud, pleft, impl, hidde_in_records, chattime, radiotime, chatclantime, chattimecaves, logme, etm, rq_ts_d, rq_id_d, rq_ts_h, rq_id_h, rq_ok, rq_st, rq_drop1_st, rq_drop2_st, rq_cnt, rq_from, distance, x, y, buy_submob_info, last_wrong_login_cnt, last_wrong_login_time, mail_limit, read_news, buy_map_pustosh, ref, unv, reg_date, wins_quest, vk_id, unv_start, unv_ban, last_visit_toxic_caves, nomail, token, travel_place, labyrinth_y, labyrinth_x, provider, provider_uid, lang_slug, remember', 'safe'),
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
//      'banById' => array(self::HAS_ONE, 'Banned', array('userid' => 'id'), 'on' => 'banById.tor=0'),
      'bannedPlayers' => array(self::HAS_ONE, 'BannedPlayers', array('player_id' => 'id'), 'on' => '(bannedPlayers.until IS NULL OR bannedPlayers.until> NOW()) AND (bannedPlayers.active_chat = 0 OR bannedPlayers.active_site = 0 OR bannedPlayers.active_advert = 0)'),
      'bannedIp' => array(self::HAS_ONE, 'BannedIp', array('ip' => 'ip')),
      'lisa' => array(self::HAS_ONE, 'PlayersMeta', array('player_id' => 'id'), 'on' => "lisa.key='".PlayersMeta::KEY_LISA."'"),
      'pet' => array(self::HAS_ONE, 'Pets', array('owner' => 'id')),
      'outpost' => array(self::HAS_ONE, 'Outposts', array('owner' => 'id')),
      'race' => array(self::BELONGS_TO, 'PlayerRace', 'race_id'),
      'appearance' => array(self::HAS_ONE, 'Appearance', ['user_id' => 'id']),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
        'id' => 'ID',
        'user' => 'Логин',
        'email' => 'Email',
        'pass' => 'Код активации email',
        'pass2' => 'Пароль',
        'question' => 'Секретный вопрос',
        'answer' => 'Ответ на секретный вопрос',
        'rank' => 'Ранг',
        'level' => 'Уровень',
        'gold' => 'Золота на руках',
        'bank' => 'Золота в банке',
        'platinum' => 'Крышек',
        'exp' => 'Опыт',
        'energy' => 'Энергия',
        'max_energy' => 'Максимальная энергия',
        'strength' => 'Сила',
        'agility' => 'Ловкость',
        'hp' => 'Жизнь',
        'max_hp' => 'Максимальная жизнь',
        'ap' => 'Очки способностей',
        'wins' => 'Побед',
        'losses' => 'Поражений',
        'tag' => 'Тэги',
        'age' => 'Возраст',
        'gender' => 'Пол',
        'location' => 'Откуда',
        'profile' => 'О себе',
        'msn' => 'Skype',
        'logins' => 'Количество входов',
        'lpv' => 'Последний раз был в игре',
        'page' => 'Заголовок текущей страницы',
        'ip' => 'Ip-адрес',
        'clan' => 'Клан',
        'ClanThresholdTime' => 'Дата и время вступления в клан',
        'PreviousClanId' => 'Предыдущий клан',
        'clan_money' => 'Казначей',
        'clan_store' => 'Кладовщик',
        'refs' => 'Количество приглашенных игроков',
        'mines' => 'Mines',
        'ops' => 'Количество ходов машины в логове охотников',
        'alethite' => 'Животный жир в логове охотников',
        'burelia' => 'Туши в логове охотников',
        'email_confirmed' => 'Адрес электронной почты подтвержден',
        'defense' => 'Защита',
        'opit' => 'Опыт работяги на базе мусорщиков',
        'napad' => 'Очки нападения',
        'ewins' => 'Агрессивный боец, уровень',
        'pohod' => 'Походные очки',
        'q_gauss' => 'Квест с Гаусом завершен?',
        'nablud' => 'Наблюдательность',
        'pleft' => 'Стимулятор, дней осталось',
        'impl' => 'Количество вживленных имплантантов',
        'hidde_in_records' => 'Не показывать в рейтинге',
        'chattime' => 'Последнее посещение общего чата',
        'radiotime' => 'Последнее посещение радио',
        'chatclantime' => 'Последнее посещение клан кладовки', 
        'chattimecaves' => 'Последнее посещение чата в пещерах',
        'logme' => 'Логировать активность',
        'etm' => 'Дата логина игрока',
        'rq_ts_d' => 'Когда истекает текущий рандомный квест?',
        'rq_id_d' => 'Идентефикатор квеста Дейзи',
        'rq_ts_h' => 'Когда истекает текущий рандомный квест?',
        'rq_id_h' => 'Идентефикатор квеста в Логове Охотников',
        'rq_ok' => 'Квест принят?',
        'rq_st' => 'Сколько мобов убито на момент взятия квеста (не учитываются)',
        'rq_drop1_st' => 'Сколько единиц первого дропа есть в инвентаре на момент взятия квеста (не учитываются)',
        'rq_drop2_st' => 'Сколько единиц второго дропа есть в инвентаре на момент взятия квеста (не учитываются)',
        'rq_cnt' => 'Сколько всего квестов выполнено',
        'rq_from' => 'Чей квест последний выполнял',
        'distance' => 'Удаленность от города',
        'x' => 'Координата X',
        'y' => 'Координата Y',
        'buy_submob_info' => 'Сколько раз обращался за информацией о супермобе',
        'last_wrong_login_cnt' => 'Количество неудачных попыток авторизации',
        'last_wrong_login_time' => 'Время последней неудачной попытки авторизации',
        'mail_limit' => 'Количество писем на странице личных сообщений',
        'read_news' => 'Есть непрочитанные новости',
        'buy_map_pustosh' => 'Карта Пустоши куплена',
        'ref' => 'Id пригласившего игрока',
        'unv' => 'Неприкосновенность',
        'reg_date' => 'Дата, время регистрации',
        'wins_quest' => 'Необходимое количество убитых соперников по квесту',
        'vk_id' => 'Id ВКонтакте',
        'unv_start' => 'Дата, время установки неприкосновенности',
        'unv_ban' => 'Принудительная неприкосновенность',
        'last_visit_toxic_caves' => 'Последний визит в токсические пещеры',
        'nomail' => 'Подписка на информационную рассылку игры Revival Online',
        'token' => 'Какой-то токен, который пишется в куки',
        'travel_place' => 'Место путешествия',
        'labyrinth_y' => 'Координата Y в лабиринте',
        'labyrinth_x' => 'Координата X в лабиринте',
        'automove' => 'Автобой',
        'lang_slug' => 'Текущий язык',
        'race_id' => 'Раса'
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

    $criteria = new CDbCriteria;

//    $criteria->order = "t.id";

    $criteria->together = true;

    $criteria->with = array(
        'bannedPlayers' => array(),
    );

    $criteria->compare("bannedPlayers.chat", $this->banChat);
    $criteria->compare("bannedPlayers.advert", $this->banAdvert);
    $criteria->compare("bannedPlayers.site", $this->banSite);

    $criteria->compare($this->tableAlias.'.id', $this->id);
    $criteria->compare($this->tableAlias.'.user', $this->user, true);
    $criteria->compare($this->tableAlias.'.email', $this->email, true);
    $criteria->compare($this->tableAlias.'.pass', $this->pass, true);
    $criteria->compare($this->tableAlias.'.pass2', $this->pass2, true);
    $criteria->compare($this->tableAlias.'.question', $this->question, true);
    $criteria->compare($this->tableAlias.'.answer', $this->answer, true);
    $criteria->compare($this->tableAlias.'.rank', $this->rank, true);
    $criteria->compare($this->tableAlias.'.level', $this->level);
    $criteria->compare($this->tableAlias.'.gold', $this->gold);
    $criteria->compare($this->tableAlias.'.exp', $this->exp);
    $criteria->compare($this->tableAlias.'.energy', $this->energy);
    $criteria->compare($this->tableAlias.'.max_energy', $this->max_energy);
    $criteria->compare($this->tableAlias.'.strength', $this->strength);
    $criteria->compare($this->tableAlias.'.agility', $this->agility);
    $criteria->compare($this->tableAlias.'.hp', $this->hp);
    $criteria->compare($this->tableAlias.'.max_hp', $this->max_hp);
    $criteria->compare($this->tableAlias.'.bank', $this->bank);
    $criteria->compare($this->tableAlias.'.ap', $this->ap);
    $criteria->compare($this->tableAlias.'.wins', $this->wins);
    $criteria->compare($this->tableAlias.'.losses', $this->losses);
    $criteria->compare($this->tableAlias.'.tag', $this->tag, true);
    $criteria->compare($this->tableAlias.'.platinum', $this->platinum);
    $criteria->compare($this->tableAlias.'.age', $this->age);
    $criteria->compare($this->tableAlias.'.gender', $this->gender, true);
    $criteria->compare($this->tableAlias.'.location', $this->location, true);
    $criteria->compare($this->tableAlias.'.profile', $this->profile, true);
    $criteria->compare($this->tableAlias.'.msn', $this->msn, true);
    $criteria->compare($this->tableAlias.'.logins', $this->logins);
    $criteria->compare($this->tableAlias.'.lpv', $this->lpv, true);
    $criteria->compare($this->tableAlias.'.page', $this->page, true);
    $criteria->compare($this->tableAlias.'.ip', $this->ip, true);
    $criteria->compare($this->tableAlias.'.clan', $this->clan);
    $criteria->compare($this->tableAlias.'.ClanThresholdTime', $this->ClanThresholdTime, true);
    $criteria->compare($this->tableAlias.'.PreviousClanId', $this->PreviousClanId, true);
    $criteria->compare($this->tableAlias.'.clan_money', $this->clan_money, true);
    $criteria->compare($this->tableAlias.'.clan_store', $this->clan_store, true);
    $criteria->compare($this->tableAlias.'.refs', $this->refs);
    $criteria->compare($this->tableAlias.'.mines', $this->mines);
    $criteria->compare($this->tableAlias.'.ops', $this->ops);
    $criteria->compare($this->tableAlias.'.alethite', $this->alethite);
    $criteria->compare($this->tableAlias.'.burelia', $this->burelia);
    $criteria->compare($this->tableAlias.'.email_confirmed', $this->email_confirmed, false);
    $criteria->compare($this->tableAlias.'.defense', $this->defense);
    $criteria->compare($this->tableAlias.'.opit', $this->opit);
    $criteria->compare($this->tableAlias.'.napad', $this->napad);
    $criteria->compare($this->tableAlias.'.ewins', $this->ewins);
    $criteria->compare($this->tableAlias.'.pohod', $this->pohod);
    $criteria->compare($this->tableAlias.'.q_gauss', $this->q_gauss, true);
    $criteria->compare($this->tableAlias.'.nablud', $this->nablud);
    $criteria->compare($this->tableAlias.'.pleft', $this->pleft);
    $criteria->compare($this->tableAlias.'.impl', $this->impl);
    $criteria->compare($this->tableAlias.'.hidde_in_records', $this->hidde_in_records);
    $criteria->compare($this->tableAlias.'.chattime', $this->chattime, true);
    $criteria->compare($this->tableAlias.'.radiotime', $this->radiotime, true);
    $criteria->compare($this->tableAlias.'.chatclantime', $this->chatclantime, true);
    $criteria->compare($this->tableAlias.'.chattimecaves', $this->chattimecaves, true);
    $criteria->compare($this->tableAlias.'.logme', $this->logme, true);
    $criteria->compare($this->tableAlias.'.etm', $this->etm, true);
    $criteria->compare($this->tableAlias.'.rq_ts_d', $this->rq_ts_d, true);
    $criteria->compare($this->tableAlias.'.rq_id_d', $this->rq_id_d);
    $criteria->compare($this->tableAlias.'.rq_ts_h', $this->rq_ts_h);
    $criteria->compare($this->tableAlias.'.rq_id_h', $this->rq_id_h);
    $criteria->compare($this->tableAlias.'.rq_ok', $this->rq_ok, true);
    $criteria->compare($this->tableAlias.'.rq_st', $this->rq_st);
    $criteria->compare($this->tableAlias.'.rq_drop1_st', $this->rq_drop1_st);
    $criteria->compare($this->tableAlias.'.rq_drop2_st', $this->rq_drop2_st);
    $criteria->compare($this->tableAlias.'.rq_cnt', $this->rq_cnt);
    $criteria->compare($this->tableAlias.'.rq_from', $this->rq_from, true);
    $criteria->compare($this->tableAlias.'.distance', $this->distance);
    $criteria->compare($this->tableAlias.'.x', $this->x);
    $criteria->compare($this->tableAlias.'.y', $this->y);
    $criteria->compare($this->tableAlias.'.buy_submob_info', $this->buy_submob_info);
    $criteria->compare($this->tableAlias.'.last_wrong_login_cnt', $this->last_wrong_login_cnt);
    $criteria->compare($this->tableAlias.'.last_wrong_login_time', $this->last_wrong_login_time, true);
    $criteria->compare($this->tableAlias.'.mail_limit', $this->mail_limit);
    $criteria->compare($this->tableAlias.'.read_news', $this->read_news);
    $criteria->compare($this->tableAlias.'.buy_map_pustosh', $this->buy_map_pustosh);
    $criteria->compare($this->tableAlias.'.ref', $this->ref);
    $criteria->compare($this->tableAlias.'.unv', $this->unv);
    $criteria->compare($this->tableAlias.'.reg_date', $this->reg_date);
    $criteria->compare($this->tableAlias.'.wins_quest', $this->wins_quest);
    $criteria->compare($this->tableAlias.'.vk_id', $this->vk_id);
    $criteria->compare($this->tableAlias.'.unv_start', $this->unv_start, true);
    $criteria->compare($this->tableAlias.'.unv_ban', $this->unv_ban);
    $criteria->compare($this->tableAlias.'.last_visit_toxic_caves', $this->last_visit_toxic_caves, true);
    $criteria->compare($this->tableAlias.'.nomail', $this->nomail);
    $criteria->compare($this->tableAlias.'.token', $this->token, true);
    $criteria->compare($this->tableAlias.'.travel_place', $this->travel_place, true);
    $criteria->compare($this->tableAlias.'.labyrinth_y', $this->labyrinth_y);
    $criteria->compare($this->tableAlias.'.labyrinth_x', $this->labyrinth_x);
    $criteria->compare($this->tableAlias.'.lang_slug', $this->lang_slug);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
        'sort'=>array(
	        'defaultOrder'=>'t.id ASC',
        ),
        'Pagination' => array (
            'PageSize' => 50 //edit your number items per page here
              ),
    ));
  }

  public function getNkrCount(){
	  $total = $this->getMeta(PlayersMeta::KEY_NKR_DOLLARS_TOTAL, 0);
	  $spent = $this->getMeta(PlayersMeta::KEY_NKR_DOLLARS_SPENT, 0);
	  return $total - $spent;
  }

  public function getWater(){
    $criteria = new CDbCriteria();
    $criteria->addCondition("owner = :id");
    $criteria->params[':id'] = $this->id;

    $model = Outposts::model()->find($criteria);
    if ($model) {
      return $model->tokens;
    } else return 0;
  }

  public function getToxicGold() {
    $model  = Toxbank::model()->findByPk($this->id);
   
    if ($model) {
      return $model->deposited_gold;
    } else return 0;
  }

  public function getBanChat(){
    $criteria = new CDbCriteria();
    $criteria->addCondition("player_id = :id");
    $criteria->addCondition("(`until` IS NULL OR `until` > now()) AND active_chat = 0");
    $criteria->addCondition("chat=1");
//    $criteria->params[':ip'] = $this->ip;
    $criteria->params[':id'] = $this->id;

    $model = BannedPlayers::model()->find($criteria);
    if ($model) {
      return $model->chat;
    } else return 0;
  }

  public function getBanSite(){
    $criteria = new CDbCriteria();
    $criteria->addCondition("player_id = :id");
    $criteria->addCondition("(`until` IS NULL OR `until` > now()) AND active_site = 0");
    $criteria->addCondition("site=1");
    $criteria->params[':id'] = $this->id;

    $model = BannedPlayers::model()->find($criteria);
    if ($model) {
      return $model->site;
    } else return 0;
  }

  public function getBanAdvert(){
    $criteria = new CDbCriteria();
    $criteria->addCondition("player_id = :id");
    $criteria->addCondition("(`until` IS NULL OR `until` > now()) AND active_advert = 0");
    $criteria->addCondition("advert=1");
    $criteria->params[':id'] = $this->id;

    $model = BannedPlayers::model()->find($criteria);
    if ($model) {
      return $model->advert;
    } else return 0;
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Players the static model class
   */
  public static function model($className = __CLASS__)
  {
    return parent::model($className);
  }

  static function getOnline($channel = false){
    $channel = $channel?$channel.'_':'';
    $all = Realplexor::getInstance()->cmdOnline('cmd_');
    $reg = array();
    foreach ($all as $key => $one) {
      $one = str_replace('cmd_'.$channel, '', $one);
      if (is_numeric($one)) {
	      $reg[] = $one;
      }
    }
    $reg[] = -1;
    return $reg;
  }

  static function isOnline($player_id){
    $players_online = self::getOnline();
    return in_array($player_id, $players_online);
  }

  static function SendCMD($to_user, $cmd, $param = array()){
    $params = array(
        'cmd' => $cmd,
        'data' => $param
    );
    return Realplexor::getInstance()->send(array("cmd_" . $to_user), json_encode($params));
  }

  public function getLinkAndName(){
    return "<a href='/view.php?view={$this->id}'>{$this->user}</a>";
  }

  public function addExperience($howMany){
	// Если заморожен опыт - не делаем ничего, вопреки всему
	if ($this->isExpFreeze()) return false;

    $this->exp += round($howMany);
    /* END MAXV */
    $needed = PlayerExp::getExperienceForNextLevel($this->level);
    if (($this->exp >= $needed) && ($this->hp > 0)){

      $this->level++;
      $this->isLevelUp = true;
      $this->ap_gain  += 3;
      $this->ap       += $this->ap_gain;
      $this->exp       = $this->exp - $needed;

      Players::SendCMD($this->id, 'popup', array(
          'title' => 'Вы поднялись на новый уровень!',
          'text'  => "Вы получили +{$this->ap_gain} свободных стата! <br> <a href=\"/ap.php\">Перейдите сюда</a>, чтобы распределить их.",
          'buttons' => array(
            'Закрыть'  => 'popupClose',
          )
      ));
      // Если заработал больше чем на 1 уровень - работаем рекурсивно
      return $this->addExperience(0);
    }
    return $this->save(false);
  }

  public function getMetaModel($key){
    $model = PlayersMeta::model()->findByAttributes(array(
      'player_id' => $this->id,
      'key'       => $key
    ));
    if (!$model){
      $model = new PlayersMeta();
      $model->player_id = $this->id;
      $model->key = $key;
    }
    return $model;
  }

  public function setMeta($key, $value){
    $model = PlayersMeta::model()->findByAttributes(array(
      'player_id' => $this->id,
      'key'       => $key
    ));
    if (!$model){
      $model = new PlayersMeta();
      $model->player_id = $this->id;
      $model->key = $key;
    }
    $model->value = $value;
    $model->save();
  }

  public function getMeta($key, $default = null){
    $model = PlayersMeta::model()->findByAttributes(array(
      'player_id' => $this->id,
      'key'       => $key
    ));
    if ($model) return $model->value;
           else return $default;
  }

  public function getBasicData(){
    $data = $this->attributes;
    unset($data['last_wrong_login_time']);
    unset($data['etm']);
    unset($data['lpv']);
    return $data;
/*
    return array(
        'gold'     => $this->gold,
        'platinum' => $this->platinum,
        'level'    => $this->level,
        'exp'      => $this->exp,
        'bank'     => $this->bank,
        'strength' => $this->strength,
        'agility'  => $this->agility,
        'hp'       => $this->hp,
        'max_hp'   => $this->max_hp,
        'clan'     => $this->clan,
        'defense'  => $this->defense,
    );
*/
  }

  public function getOverallStat(){
  	if ($this->overallData) return $this->overallData;
  	$data = (object)$this->attributes;
	$add  = game_get_player_equipment_stats($this->id);
	$data->agility += $add->add_agility;
	$data->strength += $add->add_strength;
	$data->defense += $add->add_defense;

	$this->overallData = $data;

    return $data;
  }

  public function login(){
    $handler = new Login_Handler(array(
      'login' => $this->user,
      'password' => $this->pass,
      'remember' => $this->remember?"true":false
    ));
    return !($handler->login());
  }

  public function register(){
    $handler = new Registration_Handler($this);

    $res = $handler->register_player();
    $result['message'] = isset($res['error'])?$res['error']:$res['success'];
    $result['result']  = isset($res['success'])?true:false;

    return $result;
  }

    public function recovery(){
        $handler = new Recovery_Handler($this->email);

        $res = $handler->restore();
        $result['message'] = isset($res['error'])?$res['error']:$res['success'];
        $result['result']  = isset($res['success'])?true:false;

        return $result;
    }

  public static function setLastKilledBy($player_id, $killer_id, $killer_name = '', $killer_type = self::KILLER_PLAYER){
    $data = array(
      'id' => $killer_id,
      'name' => $killer_name,
      'type' => $killer_type
    );
    $model = Players::model()->findByPk($player_id);
    /** @var $model Players */
    $model->setMeta(PlayersMeta::KEY_LAST_KILLED_BY, CJavaScript::jsonEncode($data));
  }

  public static function getLastKilledBy($player_id){
    $model = Players::model()->findByPk($player_id);
    /** @var $model Players */
    $result = $model->getMeta(PlayersMeta::KEY_LAST_KILLED_BY, false);

    $name = ' - ';
    if ($result) {
      $result = CJavaScript::jsonDecode($result, false);
      switch ($result->type) {
        case self::KILLER_PLAYER:
          $name = isset($result->name)?$result->name:false;
          if (!$name && isset($result->id)) {
            $player = Players::model()->findByPk($result->id);
            $name = $player->user;
          }
          break;
        case self::KILLER_MONSTER:
          $name = t::getDb('name', 'npc', 'id', $result->id);
          if (!$name) {
            $name = $result->name;
          }
          break;
      }
    }
    return $name;
  }

  public static function setLastKilled($player_id, $killer_id, $killer_name = '', $killer_type = self::KILLER_PLAYER){
    $data = array(
      'id' => $killer_id,
      'name' => $killer_name,
      'type' => $killer_type
    );
    $model = Players::model()->findByPk($player_id);
    /** @var $model Players */
    $model->setMeta(PlayersMeta::KEY_LAST_KILLED, CJavaScript::jsonEncode($data));
  }

  public static function getLastKilled($player_id){
    $model = Players::model()->findByPk($player_id);
    /** @var $model Players */
    $result = $model->getMeta(PlayersMeta::KEY_LAST_KILLED, false);

    $name = ' - ';
    if ($result) {
      $result = CJavaScript::jsonDecode($result, false);
      switch ($result->type) {
        case self::KILLER_PLAYER:
          $name = isset($result->name)?$result->name:false;
          if (!$name && isset($result->id)) {
            $player = Players::model()->findByPk($result->id);
            $name = $player->user;
          }
          break;
        case self::KILLER_MONSTER:
          $name = t::getDb('name', 'npc', 'id', $result->id);
          if (!$name) {
            $name = $result->name;
          }
          break;
      }
    }
    return $name;
  }

  public function setToken(){
    $token = md5(time() . $this->user);
    setcookie(self::REMEMBER_COOKIE, $token, time() + 60 * 60 * 24 * 60, '/', '.'.BASE_DOMAIN); // Куки устанавливаются на 60 суток
    $this->token = $token;
    $this->save(false);
    return $token;
  }

  public static function clearToken(){
    setcookie(self::REMEMBER_COOKIE, "", time()-3600, '/', '.'.BASE_DOMAIN);
  }

  protected function processRace($race, $add = true){
	  if ($race && $race instanceof PlayerRace){
		  /** @var $race PlayerRace */
		  foreach ($race->benefits as $benefit){
			  /** @var $benefit PlayerRaceBenefit */
			  $attribute = $benefit->benefit->field;
			  if ($this->hasAttribute($attribute)){
				  if ($add) {
					  $this->setAttribute($attribute, $this->getAttribute($attribute) + $benefit->value);
				  } else {
					  $this->setAttribute($attribute, $this->getAttribute($attribute) - $benefit->value);
				  }
			  }
		  }
	  }
  }

  public function beforeSave() {
	  if (!$this->isNewRecord) {
		  $old = Players::model()->findByPk($this->id);
		  if ($old->race_id != $this->race_id){
			  $this->processRace($old->race, false);
			  $this->processRace($this->race, true);
		  }
	  } else {
		  $this->processRace($this->race, true);
	  }
	  return parent::beforeSave(); // TODO: Change the autogenerated stub
  }

  public function raceBenefit($benefit_id){
	  $model = PlayerRaceBenefit::model()->findByAttributes([
	  	'race_id' => $this->race_id,
		 'benefit_id' => $benefit_id
	  ]);
	  if ($model) {
		  switch ($model->benefit->type){
			  case PlayerRaceBenefit::TYPE_YESNO:
			  	return (bool)$model->value;
			  case PlayerRaceBenefit::TYPE_INTEGER:
				  return (int)$model->value;
			  default:
				  return $model->value;
		  }
	  } else return false;
  }

  /**
   * @return PlayerAppearance
   */
  public function getAppearance($layoutId){
	  return PlayerAppearance::model()->findByAttributes([
	  	'player_id' => $this->id,
		'appearance_layout_id' => $layoutId
	  ]);
  }
  public function getAppearanceId($layoutId){
	  if ($model = $this->getAppearance($layoutId)){
		  return $model->player_race_appearance_id;
	  } else return false;
  }

  public function isExpFreeze(){
	  $until = $this->getMeta('freeze_exp_until', 0);
	  return (time() < $until);
  }

    public function popupInvite() {
        $repeat_period = 10;
        $remind_period = 3;

        $upopup_meta = $this->getMeta('invite_popup');
        if ($upopup_meta) {
            $popup_params = unserialize($upopup_meta);
        } else {
            $popup_params = array('last_shown'=>0,'remind'=>0,'delete'=>0);
        }

        $show = (!$popup_params['delete'])?(time()-$popup_params['last_shown']>86400*(($popup_params['remind'])?$remind_period:$repeat_period)):false;

        if ($show) {
            $popup_params['last_shown'] = time();
            $popup_params['remind'] = 0;
            $this->setMeta('invite_popup', serialize($popup_params));
            $popup_buttons = array(
                'Пригласить'=> 'btn_user_invite',
                'Отложить'=> 'btn_user_invite_remind',
                'Никогда больше не показывать'=> 'btn_user_invite_delete'
            );

            Popup::add($this->id, "Отправьте пригласительный радиосигнал своим друзьям, бесцельно скитающимся в Пустоши, и получите крышки от администрации Water City!",[],Popup::ACTION_SHOW_POPUP,$popup_buttons);

        }
    }

    public static function isUniqueIp($ip) {
        $ips_sql = "SELECT COUNT(*) FROM ips WHERE `ip`='$ip'";
        $numIps = Yii::app()->db->createCommand($ips_sql)->queryScalar();
        if ($numIps==0) {
            $soc_sql = "SELECT COUNT(*) FROM rev_invite_soc WHERE `ip`=inet_aton('$ip')";
            $numSoc = Yii::app()->db->createCommand($soc_sql)->queryScalar();
            if ($numSoc == 0) {
                $player_sql = "SELECT COUNT(*) FROM players WHERE `ip`='$ip'";
                $numPlayer = Yii::app()->db->createCommand($player_sql)->queryScalar();
                if ($numPlayer == 0) {
                    $banned_sql = "SELECT COUNT(*) FROM banned_ip WHERE `ip`=inet_aton('$ip')";
                    $numBanned = Yii::app()->db->createCommand($banned_sql)->queryScalar();
                    if ($numBanned == 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isUniqueRefIp($ref_id,$ref_ip) {
        $player_sql = "SELECT COUNT(*) FROM players WHERE `ip`='$ref_ip' AND (id='{$this->id}' || (ref='{$this->id}' AND id!='{$ref_id}'))";
        $numplayer = Yii::app()->db->createCommand($player_sql)->queryScalar();
        return ($numplayer==0);
    }

    public function checkRefExist($email) {
        $player_sql = "SELECT COUNT(*) FROM players WHERE ref='{$this->id}' AND email='{$email}'";
        $numplayer = Yii::app()->db->createCommand($player_sql)->queryScalar();
        return ($numplayer>0);
    }

    public function getInviterCaps($percent) {
        $caps_sql = "SELECT amount FROM paymentwall WHERE user_id='{$this->id}'";
        $caps = Yii::app()->db->createCommand($caps_sql)->queryAll();
        $caps_summ = 0;

        foreach ($caps as $one) {
            $caps_summ += round($one['amount']*$percent);
        }
        return $caps_summ;
    }

    public static function activationForm(){
	    $controller = new GameController('activation');
	    return $controller->widget('application.components.widgets.Activation', [], true);
    }
}