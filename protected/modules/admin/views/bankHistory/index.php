<?php
$this->breadcrumbs=array(
	'Банковские переводы'=>array('index'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('bank-history-grid', {
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

<?php $this->widget('booster.widgets.TbGridView',array(
	'id'=>'bank-history-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'dt',
		'from_player' => [
			'name'  => 'from_player',
			'type'  => 'raw',
			'value' => '$data->from_player?CHtml::link("{$data->playerFromModel->user} [{$data->from_player}]", array("/admin/players/update", "id" => $data->from_player)):""'
		],
		'to_player' => [
			'name'  => 'to_player',
			'type'  => 'raw',
			'value' => '$data->to_player?CHtml::link("{$data->playerToModel->user} [{$data->to_player}]", array("/admin/players/update", "id" => $data->to_player)):""'
		],
		'gold',
		'type' => array(
			'name'        => 'type',
			'filter'      => CHtml::dropDownList( 'BankHistory[type]', $model->type,
				[null => '-'] + $model->typesAvailable
			),
			'value' => '$data->getTypeName()'
		),
	),
)); ?>
