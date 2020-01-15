<?php

/**
 * This is the model class for table "rquests".
 *
 * The followings are the available columns in table 'rquests':
 * @property integer $id
 * @property integer $minlev
 * @property integer $maxlev
 * @property string $npcs
 * @property integer $drop1
 * @property integer $drop2
 * @property integer $amount
 * @property integer $drop1_amount
 * @property integer $drop2_amount
 * @property integer $item
 * @property integer $itemcount
 * @property integer $plat
 * @property integer $gold
 * @property string $opis
 * @property string $q_check
 * @property string $q_done
 * @property integer $qitem1
 * @property integer $qitem1count
 * @property integer $qrecipe1
 * @property integer $qrecipe1count
 * @property integer $qitem2
 * @property integer $qitem2count
 * @property integer $qrecipe2
 * @property integer $qrecipe2count
 * @property integer $qitem3
 * @property integer $qitem3count
 * @property integer $qrecipe3
 * @property integer $qrecipe3count
 * @property integer $qitem4
 * @property integer $qitem4count
 * @property integer $qrecipe4
 * @property integer $qrecipe4count
 * @property integer $experience
 * @property integer $pvp
 * @property string $questor
 * @property integer $enabled
 */
class Quest extends MLModel
{
	const QUESTOR_DAIZY = 'D';
	const QUESTOR_HUNTER = 'H';

	public $MLFields = array('opis', 'q_check', 'q_done');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rquests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('npcs, opis, q_check, q_done', 'required'),
			array('minlev, maxlev, drop1, drop2, amount, drop1_amount, drop2_amount, item, itemcount, plat, gold, qitem1, qitem1count, qrecipe1, qrecipe1count, qitem2, qitem2count, qrecipe2, qrecipe2count, qitem3, qitem3count, qrecipe3, qrecipe3count, qitem4, qitem4count, qrecipe4, qrecipe4count, experience, pvp, enabled', 'numerical', 'integerOnly'=>true),
			array('npcs', 'length', 'max'=>64),
			array('questor', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, minlev, maxlev, npcs, drop1, drop2, amount, drop1_amount, drop2_amount, item, itemcount, plat, gold, opis, q_check, q_done, qitem1, qitem1count, qrecipe1, qrecipe1count, qitem2, qitem2count, qrecipe2, qrecipe2count, qitem3, qitem3count, qrecipe3, qrecipe3count, qitem4, qitem4count, qrecipe4, qrecipe4count, experience, pvp, questor', 'safe', 'on'=>'search'),
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
			'minlev' => 'Минимальный уровень игрока',
			'maxlev' => 'Максимальный уровень игрока',
			'npcs' => 'Типы мобов, подлежащих убиению (через запятую)',
			'drop1' => 'Первый предмет, который надо собирать',
			'drop1_amount' => 'Количество предметов первого типа.',
			'drop2' => 'Второй предмет, который надо собирать',
			'drop2_amount' => 'Количество предметов второго типа.',
			'amount' => 'Сколько мобов убить надо',
			'item' => 'Предмет в награду',
			'itemcount' => 'Количество предметов в награду',
			'plat' => 'Количество крышек, выдаваемых в качестве приза',
			'gold' => 'Количество золота, выдаваемое в качестве приза',
			'opis' => 'Описание квеста',
			'q_check' => 'Квест еще не выполнен (текст)',
			'q_done' => 'Квест выполнен (текст)',
			'qitem1'        => 'Предмет №1, необходимый для данного квеста',
			'qitem1count'   => 'количество предметов №1 необходимых для данного квеста',
			'qrecipe1'      => 'Рецепт №1, необходимый для данного квеста',
			'qrecipe1count' => 'количество рецептов №1 необходимых для данного квеста',

			'qitem2'        => 'Предмет №2, необходимый для данного квеста',
			'qitem2count'   => 'количество предметов №2 необходимых для данного квеста',
			'qrecipe2'      => 'Рецепт №2, необходимый для данного квеста',
			'qrecipe2count' => 'количество рецептов №2 необходимых для данного квеста',

			'qitem3'        => 'Предмет №3, необходимый для данного квеста',
			'qitem3count'   => 'количество предметов №3 необходимых для данного квеста',
			'qrecipe3'      => 'Рецепт №3, необходимый для данного квеста',
			'qrecipe3count' => 'количество рецептов №3 необходимых для данного квеста',

			'qitem4'        => 'Предмет №4, необходимый для данного квеста',
			'qitem4count'   => 'количество предметов №4 необходимых для данного квеста',
			'qrecipe4'      => 'Рецепт №4, необходимый для данного квеста',
			'qrecipe4count' => 'количество рецептов №4 необходимых для данного квеста',

			'experience'    => 'опыт за выполнение квеста (процент от уровня)',
			'pvp'           => 'Количество побед на арене',
			'questor'       => 'Чей квест',

			'enabled'       => 'Разрешен',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('minlev',$this->minlev);
		$criteria->compare('maxlev',$this->maxlev);
		$criteria->compare('npcs',$this->npcs,true);
		$criteria->compare('drop1',$this->drop1);
		$criteria->compare('drop2',$this->drop2);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('drop1_amount',$this->drop1_amount);
		$criteria->compare('drop2_amount',$this->drop2_amount);
		$criteria->compare('item',$this->item);
		$criteria->compare('itemcount',$this->itemcount);
		$criteria->compare('plat',$this->plat);
		$criteria->compare('gold',$this->gold);
//		$criteria->compare('opis',$this->opis,true);
		$criteria->compare('q_check',$this->q_check,true);
		$criteria->compare('q_done',$this->q_done,true);
		$criteria->compare('qitem1',$this->qitem1);
		$criteria->compare('qitem1count',$this->qitem1count);
		$criteria->compare('qrecipe1',$this->qrecipe1);
		$criteria->compare('qrecipe1count',$this->qrecipe1count);
		$criteria->compare('qitem2',$this->qitem2);
		$criteria->compare('qitem2count',$this->qitem2count);
		$criteria->compare('qrecipe2',$this->qrecipe2);
		$criteria->compare('qrecipe2count',$this->qrecipe2count);
		$criteria->compare('qitem3',$this->qitem3);
		$criteria->compare('qitem3count',$this->qitem3count);
		$criteria->compare('qrecipe3',$this->qrecipe3);
		$criteria->compare('qrecipe3count',$this->qrecipe3count);
		$criteria->compare('qitem4',$this->qitem4);
		$criteria->compare('qitem4count',$this->qitem4count);
		$criteria->compare('qrecipe4',$this->qrecipe4);
		$criteria->compare('qrecipe4count',$this->qrecipe4count);
		$criteria->compare('experience',$this->experience);
		$criteria->compare('pvp',$this->pvp);
		$criteria->compare('questor',$this->questor,true);
		$criteria->compare('enabled',$this->enabled);

		parent::applyCriteria($criteria);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getQuestorName(){
		switch ($this->questor){
			case self::QUESTOR_DAIZY:
				return "Дейзи";
				break;
			case self::QUESTOR_HUNTER:
				return "Логово охотников";
				break;
			default:
				return "???";
		}
	}

	public function getEnabledDropdown()
	{
		$stats = array(
			0 => 'Запрещен',
			1 => 'Разрешен',
		);
		return CHtml::dropDownlist('enabled',$this->enabled,$stats, array(
			'class'     => 'enabled-state',
			'data-id'   => $this->id,
		));
	}
}
