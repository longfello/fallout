<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'chat-avatar-base-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php echo $form->errorSummary($model); ?>

    <?php
    if ($model->image) {
        echo CHtml::tag('br');
        echo CHtml::image("/images/chat/avatars/".$model->image, $model->avatar_id, ['style' => 'max-width:128px;']);
        echo CHtml::tag('br');
    }
    echo $form->fileFieldGroup($model, 'image_tmp');
    ?>

    <?php echo $form->textFieldGroup($model,'owner',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5', 'id'=>'playerAutocomplete','readonly'=>($model->isNewRecord)?false:true)))); ?>

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