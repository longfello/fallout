<?php
/**
 * @var $form MLActiveForm
 * @var $model Recipes
 */
$form=$this->beginWidget('MLActiveForm',array(
	'id'=>'recipes-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

    <?php echo $form->dropDownListGroup($model,'recipe_type_id', array(
        'widgetOptions'=>array(
            'data' => CHtml::listData( RecipeTypes::model()->findAll( array( 'order' => 'recipe_type_name' ) ), 'recipe_type_id', 'recipe_type_name' ),
            'htmlOptions'=>array('class'=>'span5'),
        )
    )); ?>


	<?php echo $form->MLtextFieldGroup($model, 'recipe_name', 'recipe_name'); ?>

	<?php echo $form->MLtextFieldGroup($model,'recipe_description', 'recipe_description'); ?>

    <?php echo $form->autocompleteInput($model,'crafting_id','Crafting', 'id', 'name', [], 1); ?>

	<?php echo $form->textFieldGroup($model,'cost',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'using_cnt',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

    <?php echo $form->dropDownListGroup($model,'location', array(
        'widgetOptions'=>array(
            'data' => $model->availableLocations,
            'htmlOptions'=>array('class'=>'span5'),
        )
    )); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Обновить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
