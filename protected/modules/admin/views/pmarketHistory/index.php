<?php
$this->breadcrumbs=array(
	'Банковские переводы'=>array('index'),
);


Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('pmarket-history-grid', {
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
'id'=>'pmarket-history-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'dt',
		'seller' => [
			'name'  => 'seller',
			'type'  => 'raw',
			'value' => '$data->seller?CHtml::link("{$data->sellerModel->user} [{$data->seller}]", array("/admin/players/update", "id" => $data->seller)):""'
		],
		'buyer' => [
			'name'  => 'buyer',
			'type'  => 'raw',
			'value' => '$data->buyer?CHtml::link("{$data->buyerModel->user} [{$data->buyer}]", array("/admin/players/update", "id" => $data->buyer)):""'
		],
		'cost',
		'count'
),
)); ?>
