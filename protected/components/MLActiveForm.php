<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 29.03.16
 * Time: 15:03
 */
class MLActiveForm extends KActiveForm
{
  const NEW_RECORD_SIGNATURE = 'new';

  private $languages;
  public function init(){
    $this->languages = t::getInstance()->languages;
    parent::init();
  }

  function MLtextAreaVisualGroup(MLModel $model, $field, $newItemSlug = 'model') {
    $content  = "<div class='{$field}-field-wrapper'>";
    $content .= $this->labelEx($model, $field);
    $content .= "<ul class='nav nav-tabs' role='tablist'>";
    $first = true;
    foreach($this->languages as $iso => $language) {
      $active = $first?"active":"";
      $content .= ("<li role='presentation' class='$active'><a href='#{$field}-{$iso}' aria-controls='{$field}-{$iso}' role='tab' data-toggle='tab'>{$language->name}</a></li>");
      $first = false;
    }
    $content .= ("</ul><div class='tab-content'>");
    $first = true;
    if ($model->isNewRecord) {
      $mlSlug = 'Translate['.self::NEW_RECORD_SIGNATURE.']['.$newItemSlug.']['.$field.']';
    } else {
      $mlSlug = 'Translate['.t::getDbSlug($field, $model->tableName(), 'id', $model->id).']';
    }
    $current_language = t::iso();
    foreach($this->languages as $iso => $language) {
      $active = $first?"active":"";
      $content .= ("<div role='tabpanel' class='tab-pane $active' id='{$field}-{$iso}'>");
      t::getInstance()->setLanguage($iso);
      $content .= $this->widget(
          'bootstrap.widgets.TbCKEditor',
          array(
              'name'  => $mlSlug."[{$iso}]",
              'value' => $model->getML($field),
	          'fileManager' => true
          ), true);
      $content .= ('<p class="help-block"></p>');
      $content .= ("</div>");
      $first = false;
    }
    t::getInstance()->setLanguage($current_language);
    $content .= "</div>";
    $content .= "</div>";
    return $content;
  }

  function MLtextFieldGroup(MLModel $model, $field, $newItemSlug = 'model') {
    $content  = "<div class='{$field}-field-wrapper'>";
    $content .= $this->labelEx($model, $field);
    $content .= "<ul class='nav nav-tabs' role='tablist'>";
    $first = true;
    foreach($this->languages as $iso => $language) {
      $active = $first?"active":"";
      $content .= ("<li role='presentation' class='$active'><a href='#{$field}-{$iso}' aria-controls='{$field}-{$iso}' role='tab' data-toggle='tab'>{$language->name}</a></li>");
      $first = false;
    }
    $content .= ("</ul><div class='tab-content'>");
    $first = true;
    if ($model->isNewRecord) {
      $mlSlug = 'Translate['.self::NEW_RECORD_SIGNATURE.']['.$newItemSlug.']['.$field.']';
    } else {
      $pk = $model->tableSchema->primaryKey;
      $mlSlug = 'Translate['.t::getDbSlug($field, $model->tableSchema->name, $pk, $model->getAttribute($pk)).']';
    }
    $current_language = t::iso();
    foreach($this->languages as $iso => $language) {
      $active = $first?"active":"";
      $content .= ("<div role='tabpanel' class='tab-pane $active' id='{$field}-{$iso}'>");
      t::getInstance()->setLanguage($iso);
      $content .= CHtml::telField($mlSlug."[{$iso}]", $model->getML($field), array('class' => 'span5 form-control'));
      $content .= ('<p class="help-block"></p>');
      $content .= ("</div>");
      $first = false;
    }
    t::getInstance()->setLanguage($current_language);
    $content .= "</div>";
    $content .= "</div>";
    return $content;
  }

  public static function save($model = null, $newItemSlug = null){
    $current_language = t::iso();
    if (is_array($newItemSlug)) {
      foreach($newItemSlug as $one){
        self::save($model, $one);
      }
    } else {
      if (Yii::app()->request->isPostRequest){
        $data = Yii::app()->request->getPost('Translate', array());
        foreach($data as $slug => $lineData){
          if ($slug == self::NEW_RECORD_SIGNATURE) {
            if ($model && $newItemSlug) {
              if (!is_array($newItemSlug)) $newItemSlug = array($newItemSlug);
              foreach($newItemSlug  as $oneNewItemSlug) {
                foreach($lineData as $newSlug => $subdata) {
                  if ($newSlug == $oneNewItemSlug) {
                    foreach($subdata as $field => $IsoAndValue) {
                      foreach($IsoAndValue as $iso => $value){
                        $lang = Language::model()->findByAttributes(array('slug' => $iso));
                        t::getInstance()->setLanguage($iso);
                        t::getDb($field, $model->tableName(), 'id', $model->id);
                        $record_slug = t::getDbSlug($field, $model->tableName(), 'id', $model->id);

                        $langModel = LanguageTranslate::model()->findByAttributes(array(
                            'lang_id' => $lang->id,
                            'slug'    => $record_slug
                        ));
                        if ($langModel) {
                          $langModel->value = $value;
                          $langModel->save();
                        }
                      }
                    }
                  }
                }
              }
            }
          } else {
            foreach($lineData as $iso => $value) {
              $lang = Language::model()->findByAttributes(array('slug' => $iso));
              t::getInstance()->setLanguage($iso);
              $langModel = LanguageTranslate::model()->findByAttributes(array(
                  'lang_id' => $lang->id,
                  'slug'    => $slug
              ));
              if ($langModel) {
                $langModel->value = $value;
                $langModel->save();
              }
            }
          }
        }
      }
    }
    t::getInstance()->setLanguage($current_language);
  }
}