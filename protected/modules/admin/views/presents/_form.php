<?php
  /**
   * @var $model Presents
   * @var $form MLActiveForm
   */
?>
<?php $form=$this->beginWidget('MLActiveForm',array(
	'id'=>'presents-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['enctype' => 'multipart/form-data']
)); ?>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->MLtextFieldGroup($model,'name','name'); ?>

	<?php echo $form->textFieldGroup($model,'price',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->dropDownListGroup($model,'hidden',array('widgetOptions'=>array('data' => [0 => 'Нет', 1=>'Да' ] ,'htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->dropDownListGroup($model,'clan',array('widgetOptions'=>array('data' => [null => ' - '] + CHtml::listData(Clans::model()->findAll(['order' => 'name', 'select' => 'id, concat(name, " [",id,"]") name']), 'id', 'name'),'htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'owner',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5', 'id'=>'playerAutocomplete')))); ?>

	<?php
		echo $form->fileFieldGroup($model, 'image');
		echo CHtml::image($model->getImageUrl(Presents::IMAGE_SMALL), '', ['style' => 'max-height:100px;max-width:300px;']);
		echo CHtml::tag('br');
		echo CHtml::tag('br');
	?>

	<?php
		echo $form->fileFieldGroup($model, 'imageBig');
		echo CHtml::image($model->getImageUrl(Presents::IMAGE_BIG), '', ['style' => 'max-height:100px;max-width:300px;']);
		echo CHtml::tag('br');
		echo CHtml::tag('br');
	?>

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
	})
</script>