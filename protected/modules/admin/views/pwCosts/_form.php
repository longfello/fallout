<?php
/**
 * @var $form TbActiveForm
 */
$form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'pw-costs-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<p class="help-block">Поля с <span class="required">*</span> обязательные.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldGroup($model,'platinum',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')), 'hint' => 'Количество приобретаемых крышек')); ?>

	<?php echo $form->textFieldGroup($model,'price',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')), 'hint' => 'Стоимость в долларах США')); ?>

	<?php echo $form->textFieldGroup($model,'discount',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')), 'hint' => 'Величина скидки, отображаемая на странице покупки крышек')); ?>

<?php echo $form->dropDownListGroup($model,'box_type', array(
	'widgetOptions'=>array(
		'data' => array(
			PwCosts::BOX_TYPE_FORCE_BOX => 'Обязательно выдать ящик, если есть хоть один',
			PwCosts::BOX_TYPE_MAY_NONE  => 'Ящик может быть не выдан, если все вероятности были ниже, чем указано для ящика',
		),
		'htmlOptions'=>array('class'=>'span5'),
	),
	'hint' => 'Выдача ящика - событие с некоторой вероятностью. Если это событие невероятно, то ящик может быть не выдан. Если невыдача ящика неуместна, можно форсировать выдачу, выбрав вариант "Обязательно выдать ящик, если есть хоть один". В данном случае алгоритм вероятностной выдачи ящиков будет повторен при необходимости несколько раз.'
)); ?>

	<?php
	echo $form->fileFieldGroup($model, 'image_tmp');
	if ($model->image) {
		echo CHtml::tag('br');
		echo CHtml::image('/images/costs/'.$model->image);
		echo CHtml::tag('br');
	}
	?>
<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
