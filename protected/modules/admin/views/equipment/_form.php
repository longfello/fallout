<?php
/**
 * @var $form MLActiveForm
 * @var $model Equipment
 */
$form=$this->beginWidget('MLActiveForm',array(
	'id'=>'equipment-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php echo $form->errorSummary($model); ?>


<div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#p1" aria-controls="p1" role="tab" data-toggle="tab">Основное</a></li>
		<li role="presentation"><a href="#p12" aria-controls="p12" role="tab" data-toggle="tab">Изображения</a></li>
		<li role="presentation"><a href="#p11" aria-controls="p11" role="tab" data-toggle="tab">Дополнительно</a></li>
		<li role="presentation"><a href="#p2" aria-controls="p2" role="tab" data-toggle="tab">Требования</a></li>
		<li role="presentation"><a href="#p3" aria-controls="p3" role="tab" data-toggle="tab">Эффекты</a></li>
		<li role="presentation"><a href="#p4" aria-controls="p4" role="tab" data-toggle="tab">Постэффекты</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="p1">
			<div class="row well">
				<div class="col-xs-12">
					<?php echo $form->MLtextFieldGroup($model,'name', 'name'); ?>
					<?php echo $form->MLtextAreaVisualGroup($model,'opis', 'opis'); ?>
					<?php echo $form->dropDownListGroup($model,'status',array('widgetOptions'=>array('data' => $model->statusAvailable,'htmlOptions'=>array('class'=>'span5','maxlength'=>1)))); ?>
					<?php echo $form->dropDownListGroup($model,'type',array('widgetOptions'=>array('data'=> $model->typesAvailable, 'htmlOptions'=>array('class'=>'span5','maxlength'=>1)))); ?>
					<?php echo $form->textFieldGroup($model,'cost',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->dropDownListGroup($model,'mtype',array('widgetOptions'=>array('data' => $model->mtypeAvaliable, 'htmlOptions'=>array('class'=>'span5','maxlength'=>1)))); ?>
					<?php echo $form->numberFieldGroup($model,'weight',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->textFieldGroup($model,'owner',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->dropDownListGroup($model,'clan',array('widgetOptions'=>array('data' => ['0' => '-- Общедоступный --']+CHtml::listData( Clans::model()->findAll( array( 'order' => 'name' ) ), 'id', 'name' ),'htmlOptions'=>array('class'=>'span5')))); ?>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p12">
			<div class="row well">
				<div class="col-xs-12">
					<div class="row">
						<p>Загружаемые изображения должны быть в формате PNG.</p>
						<?php
						foreach ($model->imagesAvailable as $type => $name) { ?>
							<div class="col-xs-4" style="height:150px;">
								<div class="row">
									<div class="col-xs-4">
										<?php
											if ($model->imageExists($type)) {
												echo CHtml::image($model->getImagePath($type), $model->name, array('class' => 'img-responsive'));
											} else {
												echo CHtml::image('/images/no_image.png', 'No photo', array('class' => 'img-responsive'));
											}
										?>
									</div>
									<div class="col-xs-8">
										<label class="control-label"><?= $name ?></label>
										<?= CHtml::fileField('upload-'.md5($type)) ?>
									</div>
	                            </div>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p11">
			<div class="row well">
				<div class="col-xs-12">
				<?php echo $form->dropDownListGroup($model,'durability',array('widgetOptions'=>array('data' => ['0' => '-- Бесконечно --']+CHtml::listData( Durability::model()->findAll( array( 'order' => 'max_life_time' ) ), 'durability', 'max_life_time' ),'htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->dropDownListGroup($model,'eprot',array('widgetOptions'=>array('data' => $model->YesNo,'htmlOptions'=>array('class'=>'span5','maxlength'=>1)))); ?>
				<?php echo $form->textFieldGroup($model,'uname',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>32)))); ?>


    		<?php echo $form->autocompleteInput($model,'product', 'Equipment', 'id', 'name', [], 100); ?>

    		<?php /* echo $form->textFieldGroup($model,'product',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)),'hint' => 'Перечисленные через запятую id предметов')); */ ?>
				<?php echo $form->dropDownListGroup($model,'class', array('widgetOptions'=>array('data'=>$model->classAvaliable, 'htmlOptions'=>array('class'=>'input-large')))); ?>
				<?php echo $form->dropDownListGroup($model,'no_weapon',array('widgetOptions'=>array('data'=>$model->YesNo,'htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->dropDownListGroup($model,'toxic',array('widgetOptions'=>array('data'=>$model->YesNo,'htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->dropDownListGroup($model,'is_caves_drop',array('widgetOptions'=>array('data'=>$model->YesNo,'htmlOptions'=>array('class'=>'span5')))); ?>
				<?php echo $form->dropDownListGroup($model,'location_use',array('widgetOptions'=>array('data'=>$model->locationAvailable,'htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>
				<?php echo $form->textFieldGroup($model,'className',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)), 'hint' => 'Для специфических предметов с индивидуальным поведением')); ?>
				<?php echo $form->textFieldGroup($model,'params',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)), 'hint' => 'Параметры специфичны для класса')); ?>
			</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p2">
			<div class="row well">
				<div class="col-xs-12">
					<?php echo $form->numberFieldGroup($model,'minlev',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'shoplvl',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'min_strength',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'min_agility',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'min_defense',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'min_max_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'min_max_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p3">
			<div class="row well">
				<div class="col-xs-12">
					<?php echo $form->numberFieldGroup($model,'time_effect',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_strength',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_agility',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_defense',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_max_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_max_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'add_pohod',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="p4">
			<div class="row well">
				<div class="col-xs-12">
					<?php echo $form->numberFieldGroup($model,'post_time_effect',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_strength',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_agility',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_defense',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_max_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_max_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
					<?php echo $form->numberFieldGroup($model,'post_pohod',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
				</div>
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
