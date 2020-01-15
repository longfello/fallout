<?php $form=$this->beginWidget('MLActiveForm',array(
	'id'=>'tatoos-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<p class="help-block">Поля с <span class="required">*</span> обязательные.</p>

<?php echo $form->errorSummary($model); ?>


<div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#p1" aria-controls="p1" role="tab" data-toggle="tab">Основное</a></li>
		<li role="presentation"><a href="#p2" aria-controls="p2" role="tab" data-toggle="tab">Ограничения</a></li>
		<li role="presentation"><a href="#p3" aria-controls="p3" role="tab" data-toggle="tab">Эффекты</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="p1">
			<div class="col-xs-2">
				<?php
				echo $form->fileFieldGroup($model, 'image_tmp');
				if ($model->picname) {
					echo CHtml::tag('br');
					echo CHtml::image('/images/tatoo/'.$model->picname.'.png');
					echo CHtml::tag('br');
				}
				?>
			</div>
			<div class="col-xs-10">
				<?php echo $form->MLtextFieldGroup($model,'name','name'); ?>
				<?php echo $form->MLtextAreaVisualGroup($model,'opis', 'opis'); ?>
				<?php echo $form->textFieldGroup($model,'cost',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->dropDownListGroup($model,'mtype', array('widgetOptions'=>array('data'=>array("gold"=>"Золото","platinum"=>"Крышки",), 'htmlOptions'=>array('class'=>'input-large')))); ?>
				<?php echo $form->textFieldGroup($model,'owner',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->dropDownListGroup($model,'clan',array('widgetOptions'=>array('data' => ['0' => '-- Общедоступный --']+CHtml::listData( Clans::model()->findAll( array( 'order' => 'name' ) ), 'id', 'name' ),'htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->hiddenField($model,'critmod'); ?>
				<?php echo $form->dropDownListGroup($model,'count', array('widgetOptions'=>array('data'=>array("unlim"=>"Неграничено","one"=>"Один раз",), 'htmlOptions'=>array('class'=>'input-large')))); ?>
				<?php echo $form->textFieldGroup($model,'days',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p2">
			<div class="col-xs-12">
				<?php echo $form->textFieldGroup($model,'minlev',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'min_strength',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'min_agility',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'min_defense',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'min_max_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'min_max_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p3">
			<div class="col-xs-12">
				<?php echo $form->textFieldGroup($model,'add_strength',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'add_agility',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'add_defense',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'add_max_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'add_max_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'regen_count',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'regen_decrease',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->textFieldGroup($model,'napad',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
			</div>
		</div>
	</div>
</div>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
