<?php
/**
 * @var $form MLActiveForm
 * @var $model PlayerRace
 * @var $benefits PlayerRaceBenefit[]
 */
  $form=$this->beginWidget('MLActiveForm',array(
	'id'=>'player-race-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->MLtextFieldGroup($model,'name', 'name'); ?>
	<?php echo $form->MLtextAreaVisualGroup($model,'description', 'description'); ?>

    <?php  foreach($benefits as $benefit){
	    $name = 'benefit['.$benefit->id.']';
        switch ($benefit->benefit->type){
            case PlayerRaceBenefit::TYPE_YESNO:
	            echo $form->dropDownListGroup($benefit, 'value', ['label' => $benefit->benefit->name,'widgetOptions' => array('htmlOptions' => ['class' => 'span5', 'name' => $name], 'data' => ['0' => 'Нет', '1' => 'Да'])]);
                break;
            case PlayerRaceBenefit::TYPE_INTEGER:
	            echo $form->numberFieldGroup($benefit, 'value', ['label' => $benefit->benefit->name,'widgetOptions' => array('htmlOptions' => ['class' => 'span5', 'name' => $name])]);
                break;
        }
	} ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
