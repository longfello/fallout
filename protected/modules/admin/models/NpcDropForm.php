<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class NpcDropForm extends CFormModel
{

	public $equipment_id;
	public $numFrom;
	public $numTo;
	public $deFrom;
	public $deTo;
	public $qFrom;
	public $qTo;
    public $npc_ids;

  /**
   * Declares the validation rules.
   * The rules state that username and password are required,
   * and password needs to be authenticated.
   */
  public function rules()
  {
    return array(
        array('equipment_id, numFrom, numTo, deFrom, deTo, qFrom, qTo, npc_ids', 'required'),
	    array('equipment_id, numFrom, numTo, deFrom, deTo, qFrom, qTo', 'numerical', 'integerOnly'=>true),
        array('npc_ids', 'length', 'max' => 16384),
        array('equipment_id, numFrom, numTo, deFrom, deTo, qFrom, qTo, npc_ids', 'safe'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels()
  {
    return array(
  	    'equipment_id' => 'Предмет',
	    'numFrom' => 'Числитель, от',
	    'numTo' => 'Числитель, до',
	    'deFrom' => 'Знаменатель, от',
	    'deTo' => 'Знаменатель, до',
	    'qFrom' => 'Количество, от',
	    'qTo' => 'Количество, до',
	    'npc_ids' => 'Мобы'
    );
  }

  public function getAttribute($attribute){
  	if (isset($this->{$attribute})){
  		return $this->{$attribute};
    }
    return null;
  }

  public function tableName(){
	  return 'NpcDropForm';
  }
}
