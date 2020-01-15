<?php
/* @var $this LanguageController */
/* @var $model Language */
/* @var $form TbActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('TbActiveForm', array(
	'id'=>'language-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldGroup($model, 'slug', array('size' => 5, 'maxlength' => 5)); ?>
	<?php echo $form->textFieldGroup($model, 'name', array('size' => 50, 'maxlength' => 50)); ?>
	<?php
	$data = array('admin' => 'Администратор');
	foreach(Yii::app()->authManager->operations as $key => $one) {
		$data[$key] = $one->description?$one->description:$key;
	}
	?>
	<?php echo $form->dropDownListGroup($model, 'authItem', array('widgetOptions' => array('data' => $data))); ?>
  <?php
    $data = array('' => ' - не задан - ');
    foreach(Language::model()->getWritableLanguages() as $key => $one) {
      if ($key != $model->slug) {
        $data[$key] = $one->name;
      }
    }
  ?>
	<?php echo $form->dropDownListGroup($model, 'fallback', array('widgetOptions' => array('data' => $data))); ?>
	<?php echo $form->dropDownListGroup($model, 'enable_game', array('widgetOptions' => array('data' => array(0 => 'Нет', 1 => 'Да')))); ?>
	<?php echo $form->dropDownListGroup($model, 'enable_home', array('widgetOptions' => array('data' => array(0 => 'Нет', 1 => 'Да')))); ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-10">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->