<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 20.04.16
 * Time: 13:20
 */
class Tools
{
  const TYPE_YES_NO = 'yes_no';
  const TYPE_TEXT   = 'text';
  const TYPE_NUMBER = 'number';

  public static function inlineEdit($model, $field, $type){
    /** @var $model CActiveRecord */
    $pk = $model->getAttribute($model->getTableSchema()->primaryKey);
    $id = uniqid('ie_').'_'.$pk;
    $view = '';
    $options = " id='{$id}' class='form-control ie-editable' data-model='".get_class($model)."' data-field='{$field}' data-id='{$pk}' ";
    switch($type) {
      case self::TYPE_NUMBER:
        if ($info = $model->getAttribute('info')) {
          if (isset($info->min) && isset($info->max)){
            $view .= "<select {$options}>";
            for($i=$info->min; $i<$info->max; $i++){
              $view .= "<option value='{$i}' ".(($model->getAttribute($field) == $i)?"selected":"").">{$i}</option>";
            }
            $view .= "</select>";
          }
        }
        if (!$view) {
          $view = "<input {$options} value='".CHtml::encode($model->getAttribute($field))."'>";
        }
        break;
      case self::TYPE_YES_NO:
        $view = "<select {$options}><option value='1' ".($model->getAttribute($field)?"selected":"").">Да</option><option value='0' ".($model->getAttribute($field)?"":"selected").">Нет</option></select>";
        break;
      default:
        $view = "<input {$options} value='".CHtml::encode($model->getAttribute($field))."'>";
    }
    $view .= "<span class='result'></span>";
    echo($view);
    Yii::app()->clientScript->registerScript("ie_update", "
$('.ie-editable').on('change', function(){
  var el   = this;
  var data = $(this).data();
  data.value = $(this).val();
  $.post('/admin/api/field', {data: data}, function(data){
    console.log(data);
    if (data && data.result){
      $(el).siblings('.result').fadeTo('fast', 1).html('Сохранено!').delay(1000).fadeTo('fast', 0);
    } else {
      if (data){
        $(el).siblings('.result').fadeTo('fast', 1).html(data.error);
      } else {
        $(el).siblings('.result').fadeTo('fast', 1).html('При сохранении произошла ошибка!');
      }
    }
  }, 'json');
});
", CClientScript::POS_READY);
  }
}