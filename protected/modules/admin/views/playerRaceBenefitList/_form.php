<?php
/***
 * @var $form MLActiveForm
 * @var $model PlayerRaceBenefitList
 */
?>
<?php $form=$this->beginWidget('MLActiveForm',array(
	'id'=>'player-race-benefit-list-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->MLtextFieldGroup($model,'name','name'); ?>
	<?php echo $form->MLtextAreaVisualGroup($model,'description','description'); ?>
	<?php echo $form->dropDownListGroup($model,'type', array('widgetOptions'=>array('data'=>array("YesNo"=>"Да/Нет","Integer"=>"Число",), 'htmlOptions'=>array('class'=>'input-large')))); ?>
	<?php echo $form->dropDownListGroup($model,'field', array('widgetOptions'=>array('data'=>array(
		"none"=>"Не влияет",
		"max_energy"=>"Энергия",
		"max_hp"=>"Здоровье",
		"strength"=>"Сила",
		"agility"=>"Ловкость",
		"defense"=>"Защита",
	), 'htmlOptions'=>array('class'=>'input-large')))); ?>

	<div class="form-actions">
		<?php $this->widget('booster.widgets.TbButton', array(
				'buttonType'=>'submit',
				'context'=>'primary',
				'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
			)); ?>
	</div>

<?php $this->endWidget(); ?>
