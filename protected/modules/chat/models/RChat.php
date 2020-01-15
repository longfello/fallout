<?php

/**
 * This is the model class for table "{{chat}}".
 *
 * The followings are the available columns in table '{{chat}}':
 * @property integer $id
 * @property integer $player_id
 * @property string $message
 * @property string $dt
 * @property integer $to_player
 * @property string $lang_slug
 */
class RChat extends CActiveRecord
{

    public $dt_start;
    public $dt_end;
    public $dt_start_t;
    public $dt_end_t;
    public $player_filter;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{chat}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('player_id, to_player', 'numerical', 'integerOnly'=>true),
            array('message, dt, lang_slug, dt_start, dt_end, dt_start_t, dt_end_t, player_filter', 'safe'),
            //array('message', 'filter', 'filter' => 'strip_tags'),
            //array('message', 'filter', 'filter' => array('MessageFormat', 'format')),
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
 	    'playerFromModel' => array(self::BELONGS_TO, 'Players', 'player_id'),
            'playerToModel' => array(self::BELONGS_TO, 'Players', 'to_player'),
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
            'message' => 'Сообщение',
            'dt' => 'Дата',
            'to_player' => t::get('Лично'),
            'lang_slug' => 'Язык',
            'dt_start' => 'Дата (от)',
            'dt_end' => 'Дата (до)',
            'dt_start_t' => 'Дата (от)',
            'dt_end_t' => 'Дата (до)'
        );
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Chat the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                $this->player_id = Yii::app()->stat->id;

                $this->dt = new CDbExpression('NOW()');

                $mf = new MessageFormat();
                $this->message = $mf->format($this->message);
            }
            return true;
        }
        else
            return false;
    }


    public function defaultScope()
    {
        return array(

        );
    }

public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('player_id',$this->player_id);
        $criteria->compare('message',$this->message,true);
        $criteria->compare('dt',$this->dt,true);
        $criteria->compare('to_player',$this->to_player);
        $criteria->compare('lang_slug',$this->lang_slug,true);

        if ($this->dt_start && !is_null($this->dt_start)) {
            $tmp_date_start = DateTime::createFromFormat('d/m/Y', $this->dt_start);
            $criteria->addCondition("dt>='".date('Y-m-d\T00:00:00P', $tmp_date_start->getTimestamp())."'");
        }

        if ($this->dt_end && !is_null($this->dt_end)) {
            $tmp_date_end = DateTime::createFromFormat('d/m/Y', $this->dt_end);
            $criteria->addCondition("dt<='".date('Y-m-d\T00:00:00P', $tmp_date_end->getTimestamp())."'");
        }

        if ($this->dt_start_t && !is_null($this->dt_start_t)) {
            $criteria->addCondition("dt>='".date(DATE_W3C, $this->dt_start_t)."'");
        }

        if ($this->dt_end_t && !is_null($this->dt_end_t)) {
            $criteria->addCondition("dt<='".date(DATE_W3C, $this->dt_end_t)."'");
        }


        if (!is_null($this->player_id)){
            if (is_numeric($this->player_id)) {
                $criteria->addCondition("player_id = {$this->player_id}");
            } else {
                if ($this->player_id) {
                    $criteria->join .= " 
						LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->player_id}%') p1 ON p1.id = t.player_id
					";

                    $criteria->addCondition("((p1.user IS NOT NULL))");
                }
            }
        }

        if (!is_null($this->to_player)){
            if (is_numeric($this->to_player)) {
                $criteria->addCondition("to_player = {$this->to_player}");
            } else {
                if ($this->to_player) {
                    $criteria->join .= " 
						LEFT JOIN (SELECT id, user FROM players WHERE user LIKE '{$this->to_player}%') p2 ON p2.id = t.to_player
					";

                    $criteria->addCondition("((p2.user IS NOT NULL))");
                }
            }
        }

        if (!is_null($this->player_filter)) {
            $criteria->addCondition("player_id = {$this->player_filter} OR to_player = {$this->player_filter}");
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
	
 public function getFormatedMessage() {
        /*$res = '';
        $formatter = new MessageFormat();
        $res = $formatter->format($this->message);
        return Extra::forMe($res);
	*/
	return str_replace('src="images','src="/images',$this->message);
    }	

}
