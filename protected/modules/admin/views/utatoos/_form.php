<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'utatoos-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Поля с <span class="required">*</span> обязательные.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldGroup($model,'owner',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5', 'id'=>'playerAutocomplete','readonly'=>($model->isNewRecord)?false:true)))); ?>

	<?php if (!$model->isNewRecord) echo $form->textFieldGroup($model,'regens',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'tatoo',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5', 'id'=>'tatooAutocomplete','readonly'=>($model->isNewRecord)?false:true)))); ?>

	<?php if (!$model->isNewRecord) echo $form->textFieldGroup($model,'lifetime',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php if (!$model->isNewRecord) echo $form->textFieldGroup($model,'timeout',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#tatooAutocomplete').autocomplete({
			source: function( request, response ) {
				$.ajax( {
					url: "/admin/api/autocomplete",
					data: {
						category: 'tatoo',
						term: request.term,
						player_id: $("#playerAutocomplete").val()
					},
					success: function( data ) {
						response( data );
					}
				} );
			},
			minLength: 1,
			select: function( event, ui ) {
				$('#tatooAutocomplete').val(ui.item);
			}
		} );

		$("#playerAutocomplete").autocomplete({
			source: function( request, response ) {
				$.ajax( {
					url: "/admin/api/autocomplete",
					data: {
						category: 'player',
						term: request.term
					},
					success: function( data ) {
						response( data );
					}
				} );
			},
			minLength: 2,
			select: function( event, ui ) {
				$('#playerAutocomplete').val(ui.item);
			}
		} );
	})
</script>