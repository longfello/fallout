<?php
  /**
   * @var $model PlayerPresents
   * @var $present Presents
   * @var $form TbActiveForm
   */
?>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'player-presents-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->dropDownListGroup($model,'present',array('widgetOptions'=>array('data'=> CHtml::listData(Presents::model()->findAll(['order' => 'id', 'select' => 'id, name']), 'id', 'name'), 'htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'giver',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5', 'id'=>'playerAutocomplete2')))); ?>

	<?php echo $form->textAreaGroup($model,'message', array('widgetOptions'=>array('htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'span8')))); ?>

	<?php echo $form->dateTimePickerGroup($model,'date',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<div class="form-actions">
		<?php $this->widget('booster.widgets.TbButton', array(
				'buttonType'=>'submit',
				'context'=>'primary',
				'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
			)); ?>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
	$(document).ready(function(){
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
		$("#playerAutocomplete2").autocomplete({
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
				$('#playerAutocomplete2').val(ui.item);
			}
		} );
	})
</script>