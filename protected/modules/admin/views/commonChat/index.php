<?php
$this->breadcrumbs=array(
	'Общий чат'=>array('index'),
);
if ($player_id) {
	$this->menu=array(
		array('label'=>'Все сообщения','url'=>array('/admin/commonChat/index')),
		array('label'=>'Игрок','url'=>array('/admin/players/update', 'id' => $player_id)),
	);
	?>
	<h1>Сообщения игрока #<?= $player_id; ?></h1>
	<?php

	Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('rchat-grid', {
	data: $(this).serialize()
	});
	return false;
	});
	");
	?>

	<div class="search-form">
		<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>
		<?=  CHtml::hiddenField('player_id', $player_id); ?>
		<div class="row">
			<div class="col-sm-6">
				<?php echo $form->datePickerGroup($model, 'dt_start',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy')),'prepend'=>'<i class="fa fa-calendar"></i>')); ?>
			</div>
			<div class="col-sm-6">
				<?php echo $form->datePickerGroup($model, 'dt_end',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy')),'prepend'=>'<i class="fa fa-calendar"></i>')); ?>
			</div>
		</div>

		<div class="form-actions">
			<?php $this->widget('booster.widgets.TbButton', array(
				'buttonType' => 'submit',
				'context'=>'primary',
				'label'=>'Поиск',
			)); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div><!-- search-form -->
	
<?php
} elseif ($mid) {
	
	$this->menu=array(
		array('label'=>'Все сообщения','url'=>array('/admin/commonChat/index')),
	);
	
	Yii::app()->clientScript->registerScript('highlite', "
		$('#rchat-grid .row_$mid').addClass('alert-success');
	");
	?>
	<h1> Сообщения с <?= date('Y-m-d H:i:s',$model->dt_start_t); ?> до <?=  date('Y-m-d H:i:s',$model->dt_end_t); ?></h1>

	<div class="search-form">
		<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>
		<?=  CHtml::hiddenField('mid', $mid); ?>
		<div class="row">
			<div class="col-sm-3">
				Промежуток выборки сообщений
			</div>
			<div class="col-sm-1">
				<?=  CHtml::dropDownList('mperiod', $mperiod, array('0.5'=>'1 час', '1'=>'2 часа', '1.5'=>'3 часа', '2.5'=>'5 часов')); ?>
			</div>
			<div class="col-sm-1">
				<div class="form-actions">
					<?php $this->widget('booster.widgets.TbButton', array(
						'buttonType' => 'submit',
						'context'=>'primary',
						'label'=>'Применить',
					)); ?>
				</div>
			</div>
		</div>

		<?php $this->endWidget(); ?>
	</div><!-- search-form -->
<?php } else {
	Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('rchat-grid', {
	data: $(this).serialize()
	});
	return false;
	});
	");
	?>

	<div class="search-form">
		<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>

		<div class="row">
			<div class="col-sm-6">
				<?php echo $form->datePickerGroup($model, 'dt_start',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy')),'prepend'=>'<i class="fa fa-calendar"></i>')); ?>
			</div>
			<div class="col-sm-6">
				<?php echo $form->datePickerGroup($model, 'dt_end',array('widgetOptions'=>array('options' => array('format' => 'dd/mm/yyyy')),'prepend'=>'<i class="fa fa-calendar"></i>')); ?>
			</div>
		</div>

		<div class="form-actions">
			<?php $this->widget('booster.widgets.TbButton', array(
				'buttonType' => 'submit',
				'context'=>'primary',
				'label'=>'Поиск',
			)); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div><!-- search-form -->
<?php } ?>

<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'rchat-grid',
	'rowCssClassExpression' => '"row_{$data->id}"',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
			'dt',
			'player_id' => [
				'name'  => 'player_id',
				'type'  => 'raw',
				'value' => '$data->player_id?CHtml::link("{$data->playerFromModel->user} [{$data->player_id}]", array("/admin/players/update", "id" => $data->player_id)):""'
			],
			'message'=> [
				'name'  => 'message',
				'type'  => 'raw',
				'value' => '$data->getFormatedMessage()'
			],
			'to_player'=> [
				'name'  => 'to_player',
				'type'  => 'raw',
				'value' => '$data->to_player?CHtml::link("{$data->playerToModel->user} [{$data->to_player}]", array("/admin/players/update", "id" => $data->to_player)):""'
			],
			'lang_slug'=>[
				'name'        => 'lang_slug',
				'filter'      => CHtml::dropDownList( 'RChat[lang_slug]', $model->lang_slug,array(null => '-', "ru" => "Русский", "en" => "Английский")),
				'value' => '$data->lang_slug'
			], array(
			'class' => 'booster.widgets.TbButtonColumn',
			'template' => '{show}',
			'buttons' => array(
				'show' => array(
					'label' => 'Посмотреть сообщения',
					'icon' => 'fa fa-crosshairs',
					'url' => 'Yii::app()->createUrl("admin/commonChat/index", array("mid"=>$data->id))',
				)
			)
		),
	),
)); ?>
