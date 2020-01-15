<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class NpcDropRemoveForm extends CFormModel
{

	public $equipment_id;

  /**
   * Declares the validation rules.
   * The rules state that username and password are required,
   * and password needs to be authenticated.
   */
  public function rules()
  {
    return array(
        array('equipment_id', 'required'),
	    array('equipment_id', 'numerical', 'integerOnly'=>true),
        array('equipment_id', 'safe'),
    );
  }

  /**
   * Declares attribute labels.
   */
  public function attributeLabels()
  {
    return array(
  	    'equipment_id' => 'Предмет',
    );
  }

  public function getAttribute($attribute){
  	if (isset($this->{$attribute})){
  		return $this->{$attribute};
    }
    return null;
  }

  public function tableName(){
	  return 'NpcDropRemoveForm';
  }
}
