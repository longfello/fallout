<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'clan-presents-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php echo $form->errorSummary($model); ?>

    <div class="col-xs-2">
        <?php
        echo $form->fileFieldGroup($model, 'image_tmp');
        if ($model->pic) {
            echo CHtml::tag('br');
            echo CHtml::image('/images/podarki/clan/'.$model->pic.'.png');
            echo CHtml::tag('br');
        }
        ?>
    </div>
    <div class="col-xs-10">

        <?php echo $form->textFieldGroup($model,'name',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

        <?php echo $form->textFieldGroup($model,'desc',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

        <?php echo $form->dropDownListGroup($model,'clan',array('widgetOptions'=>array('data' => ['' => '-- Не выбран --']+CHtml::listData( Clans::model()->findAll( array( 'order' => 'id' ) ), 'id', 'name' ),'htmlOptions'=>array('class'=>'span5')))); ?>
    </div>
<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
