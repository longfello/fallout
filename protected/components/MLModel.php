<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 29.03.16
 * Time: 17:24
 */
class MLModel extends CActiveRecord {
  public $MLFields = array();
  public $ml = array();
  public $layout = t::MODEL_GAME;

  public function afterFind(){
    parent::afterFind();
    foreach($this->MLFields as $field){
      $this->setAttribute($field, t::getDb($field, $this->tableSchema->name, $this->tableSchema->primaryKey, $this->getAttribute($this->tableSchema->primaryKey), false, $this->layout));
    }
  }

  public function beforeValidate(){
    foreach($this->MLFields as $field) {
      $this->setAttribute($field, 'ml');
    }
    return parent::beforeValidate();
  }

  protected function afterSave(){
    parent::afterSave();
    MLActiveForm::save($this, $this->MLFields);
  }

  public function setAttributeFromModel($toField, MLModel $fromModel, $fromField){
	  foreach (t::getInstance()->languages as $language){
		  $this->setML($toField, $fromModel->getML($fromField, $language->slug), $language->slug);
	  }
  }

  public function getML($field, $iso = false){
  	if ($iso) {
  		$current = t::iso();
	    t::getInstance()->setLanguage($iso);
	    $value = t::getDb($field, $this->tableSchema->name, $this->tableSchema->primaryKey, $this->getAttribute($this->tableSchema->primaryKey), false, $this->layout);
	    t::getInstance()->setLanguage($current);
    } else {
	    $value = t::getDb($field, $this->tableSchema->name, $this->tableSchema->primaryKey, $this->getAttribute($this->tableSchema->primaryKey), false, $this->layout);
    }
	  return $value;
  }

  public function setML($field, $value, $iso = false){
	  $iso = $iso?$iso:t::iso();
	  $language = Language::model()->findByAttributes(array('slug' => $iso));

	  $text = t::getDbSlug($field, $this->tableSchema->name, $this->tableSchema->primaryKey, $this->getAttribute($this->tableSchema->primaryKey));

	  $_model = LanguageTranslate::model();
	  switch($this->layout) {
		  case t::MODEL_GAME:
			  $_model = LanguageTranslate::model();
			  break;
		  case t::MODEL_HOME:
			  $_model = LanguageTranslateHome::model();
			  break;
	  }
	  $model = $_model->findByAttributes(array('slug' => $text, 'lang_id' => $language->id));
	  if (!$model) {
		  $model = new LanguageTranslate();
		  switch($this->layout) {
			  case t::MODEL_GAME:
				  $model = new LanguageTranslate();
				  break;
			  case t::MODEL_HOME:
				  $model = new LanguageTranslateHome();
				  break;
		  }
		  $model->lang_id = $language->id;
		  $model->slug = $text;
	  }

	  $model->value = $value;
	  if (!$model->save()) {
		  echo(current($model->getErrors()));
		  die();
	  }
  }

  public function t($field, $params = false){
    return t::getDb($field, $this->tableSchema->name, $this->tableSchema->primaryKey, $this->getAttribute($this->tableSchema->primaryKey), $params, $this->layout);
  }

	public function transliterate($text, $toLowCase = TRUE, $maxlength = 255) {
		$matrix=array(
			"й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
			"г"=>"g","ш"=>"sh","щ"=>"shch","з"=>"z","х"=>"h","ъ"=>"",
			"ф"=>"f","ы"=>"y","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
			"о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"e","ё"=>"e",
			"я"=>"ya","ч"=>"ch","с"=>"s","м"=>"m","и"=>"i","т"=>"t",
			"ь"=>"","б"=>"b","ю"=>"yu",
			"Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
			"Г"=>"G","Ш"=>"SH","Щ"=>"SHCH","З"=>"Z","Х"=>"X","Ъ"=>"",
			"Ф"=>"F","Ы"=>"Y","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
			"О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"E","Ё"=>"E",
			"Я"=>"YA","Ч"=>"CH","С"=>"S","М"=>"M","И"=>"I","Т"=>"T",
			"Ь"=>"","Б"=>"B","Ю"=>"YU",
			"«"=>"","»"=>""," "=>"-",
			"\""=>"", "\."=>"", "–"=>"-", "\,"=>"", "\("=>"", "\)"=>"",
			"\?"=>"", "\!"=>"", "\:"=>"","\r" =>"", "\n" => "",

			'#' => '', '№' => '',' - '=>'-', '/'=>'-', '  '=>'-',
		);

		// Enforce the maximum component length
		$text = implode(array_slice(explode('<br>',wordwrap(trim(strip_tags(html_entity_decode($text))),$maxlength,'<br>',false)),0,1));
		//$text = substr(, 0, $maxlength);

		foreach($matrix as $from=>$to)
			$text=mb_eregi_replace($from,$to,$text);

		// Optionally convert to lower case.
		if ($toLowCase)
		{
			$text = strtolower($text);
		}

		return $text;
	}

	public function applyCriteria(CDbCriteria &$criteria){
		$i = 0;
		foreach($this->MLFields as $field){
			$i++;
			if ($this->$field) {
				$mlCriteria = new CDbCriteria();
				$tableAlias = $this->tableAlias."_{$field}_{$i}";
				$mlCriteria->join = "
LEFT JOIN (
  SELECT id, CONCAT('@@@".$this->tableName()."@@{$field}@@".$this->tableSchema->primaryKey."@@', ".$this->tableName().".{$this->tableSchema->primaryKey}) slug 
  FROM `".$this->tableName()."` 
) {$tableAlias}_t ON {$tableAlias}_t.".$this->tableSchema->primaryKey." = {$this->tableAlias}.".$this->tableSchema->primaryKey."
LEFT JOIN (
  SELECT `value`, slug FROM rev_language_translate WHERE slug LIKE '@@@".$this->tableName()."@@{$field}@@".$this->tableSchema->primaryKey."@@%'
) {$tableAlias} ON {$tableAlias}.slug = {$tableAlias}_t.slug
";
				$mlCriteria->addCondition("{$tableAlias}.`value` LIKE '%". ( $this->getAttribute($field)) ."%' ");
				$mlCriteria->distinct = true;

				$criteria->mergeWith($mlCriteria);
			}
		}
	}

}