<?php

/**
 * This is the model class for table "cstore_h".
 *
 * The followings are the available columns in table 'cstore_h':
 * @property integer $id
 * @property integer $from_player
 * @property integer $to_player
 * @property integer $item
 * @property integer $cnt
 * @property integer $gold
 * @property integer $platinum
 * @property integer $clan
 * @property string $dt
 */
class CstoreHistory extends MLModel
{
	public $subject;
	public $operation;

	public $operation_filter;
	public $item_filter;
	public $dt_start;
	public $dt_end;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cstore_h';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from_player, to_player, item, cnt, clan, dt', 'required'),
			array('from_player, to_player, item, cnt, gold, platinum, clan', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from_player, to_player, item, cnt, gold, platinum, clan, dt, subject, operation, operation_filter, item_filter, dt_start, dt_end', 'safe', 'on'=>'search'),
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
			'from_player' => 'От кого',
			'to_player' => 'Кому',
			'item' => 'Предмет',
			'cnt' => 'Количество предметов',
			'gold' => 'Золото',
			'platinum' => 'Крышек',
			'clan' => 'ID клана из clans',
			'dt' => 'Дата передачи',
			'subject' => 'Предмет выдачи',
			'operation' => 'Кто',
			'operation_filter' => 'Тип операции',
			'item_filter' => 'Тип предмета',
			'dt_start' => 'Дата передачи (от)',
			'dt_end' => 'Дата передачи (до)'
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
		$criteria->compare('from_player',$this->from_player);
		$criteria->compare('to_player',$this->to_player);
		$criteria->compare('item',$this->item);
		$criteria->compare('cnt',$this->cnt);
		$criteria->compare('gold',$this->gold);
		$criteria->compare('platinum',$this->platinum);
		$criteria->compare('clan',$this->clan);
		$criteria->compare('dt',$this->dt,true);

		if ($this->subject) {
			if (is_numeric($this->subject)) {
				$criteria->addCondition("( (gold = {$this->subject}) || (platinum = {$this->subject}) || (item = {$this->subject}) )");
			} else {
				$criteria->join .= " LEFT JOIN (
					SELECT foo.id, tr.value name FROM(
					  SELECT *, CONCAT('@@@equipment@@name@@id@@', t.id) slug FROM `equipment` `t` 
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@equipment@@name@@id@@%' AND lang_id IN (1, 2)
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%{$this->subject}%' OR foo.id = '{$this->subject}') 
					ORDER BY tr.value
			) e ON e.id = t.item ";
				$criteria->compare('e.name', $this->subject, true);
			}
		}

		if (!is_null($this->operation)){
			if (is_numeric($this->operation)) {
				if ($this->operation == 0) {
					$criteria->addCondition("((from_player = 0))");
				} else {
					$criteria->addCondition("((from_player = {$this->operation}) || (to_player = {$this->operation}))");
				}
			} else {
				if ($this->operation == 'Администрация'){
					$criteria->addCondition("from_player = 0");
				} else {
					if ($this->operation) {
						$criteria->join .= " 
LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->operation}%') p1 ON p1.id = t.from_player
LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->operation}%') p2 ON p2.id = t.to_player
";
						$criteria->addCondition("((p1.user IS NOT NULL) || (p2.user IS NOT NULL))");
					}
				}
			}
		}

		if ($this->operation_filter) {
			switch ($this->operation_filter) {
				case 'put':
					$criteria->addCondition("(from_player != 0) AND (to_player = 0)");
					break;
				case 'give':
					$criteria->addCondition("to_player != 0");
					break;
				case 'gift':
					$criteria->addCondition("(from_player = 0) AND (to_player = 0) AND (cnt>0 || gold>0 || platinum>0)");
					break;
				case 'remove':
					$criteria->addCondition("(from_player = 0) AND (to_player = 0) AND (cnt<0 || gold<0 || platinum<0)");
					break;
			}
		}

		if ($this->item_filter && !is_null($this->item_filter)) {
			$criteria->addCondition($this->item_filter."!=0");
		}


		if ($this->dt_start && !is_null($this->dt_start)) {
			$tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
			$criteria->addCondition("dt>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
		}

		if ($this->dt_end && !is_null($this->dt_end)) {
			$tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
			$criteria->addCondition("dt<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'Pagination' => array (
				'PageSize' => 50
			),
			'sort'=>array(
				'defaultOrder'=>'dt DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CstoreHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getPlayerLink(){
		$text = "";

		if ($this->from_player==0){
			if ($this->gold!=0 || $this->platinum!=0) {
				if ($this->gold>0 || $this->platinum>0) {
					$text = "Администрация выдала в казну клана";
				} else {
					$text = "Администрация забрала из казны клана";
				}
			} else {
				if ($this->cnt>0) {
					$text = "Администрация положила в кладовку";
				} else {
					$text = "Администрация изъяла из кладовки";
				}
			}
		} else {
			$player  = Players::model()->findByPk($this->from_player);

			$playerFrom = ($player) ?CHtml::link("{$player->user} [$player->id]", ['/admin/players/update', 'id' => $player->id], ['target' => '__blank']):'Удаленный игрок #'.$this->from_player;

			if ($this->to_player == 0){
				$text = ($this->gold>0 || $this->platinum>0)?"$playerFrom положил в казну клана":"$playerFrom положил в кладовку";
			} else {
				$player2 = Players::model()->findByPk($this->to_player);
				$playerTo   = ($player2)?CHtml::link("{$player2->user} [$player2->id]", ['/admin/players/update', 'id' => $player2->id], ['target' => '__blank']):'Удаленный игрок #'.$this->to_player;
				$text = "$playerFrom выдал $playerTo";
			}
		}

		return $text;
	}

	public function getItemLink(){
		$value = '';
		if ($this->item && $this->cnt) {
			$model = Equipment::model()->findByPk($this->item);
			if ($model) {
				$value .= "{$this->cnt} x {$model->name} [{$model->id}]";
			} else {
				$value .= "{$this->cnt} x Удаленный предмет #{$this->item}";
			}
		}
		if ($this->gold) $value .= "{$this->gold}<img src='/images/gold.png'>";
		if ($this->platinum) $value .= "{$this->platinum}<img src='/images/platinum.png'>";
		return $value;
	}
}

