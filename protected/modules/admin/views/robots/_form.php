<?php $form = $this->beginWidget( 'booster.widgets.TbActiveForm', array(
	'id'                   => 'robots-form',
	'enableAjaxValidation' => false,
) ); ?>

<?php echo $form->errorSummary( $model ); ?>

<?php echo $form->textFieldGroup( $model, 'domain', array(
	'widgetOptions' => array(
		'htmlOptions' => array(
			'class'     => 'span5',
			'maxlength' => 255
		)
	)
) ); ?>

<?php echo $form->textAreaGroup( $model, 'content', array(
	'widgetOptions' => array(
		'htmlOptions' => array(
			'rows'  => 6,
			'cols'  => 50,
			'class' => 'span8'
		)
	)
) ); ?>

<div class="form-actions">
	<?php $this->widget( 'booster.widgets.TbButton', array(
		'buttonType' => 'submit',
		'context'    => 'primary',
		'label'      => $model->isNewRecord ? 'Добавить' : 'Сохранить',
	) ); ?>
</div>

<?php $this->endWidget(); ?>
