<?php /** @var $form TbActiveForm */ ?>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'type' => TbActiveForm::TYPE_INLINE,
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
		<?php echo $form->dropDownListGroup($model,'category',  array('widgetOptions' => array('data' => array('' => 'Все', 'player' => 'Игроки', 'security' => 'Безопасность')), 'htmlOptions' => array('class'=>'span5 form-control','maxlength'=>64))); ?>
		<?php echo $form->dropDownListGroup($model,'event', array('widgetOptions' => array('data' => array('' => 'Все', 'register' => 'Регистрация', 'change' => 'Редактирование', 'hack' => 'Взлом', 'gift'=>'Выдача ресурсов', 'remove'=>'Изъятие ресурсов')), 'htmlOptions' => array('class'=>'span5 form-control','maxlength'=>64)));  ?>

		<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType' => 'submit',
			'context'=>'primary',
			'label'=>'Фильтровать',
		)); ?>

<?php $this->endWidget(); ?>
