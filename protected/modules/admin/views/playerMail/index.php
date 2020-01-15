<?php
$this->breadcrumbs=array(
	'Игрок #'.$player_id => array('/admin/players/update', 'id' => $player_id),
	'Внутриигровая почта',
);

$this->menu=array(
	array('label'=>'Игрок','url'=>array('/admin/players/update', 'id' => $player_id)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('mail-grid', {
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
'id'=>'mail-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'dt',
		'sender'=> [
			'name'  => 'sender',
			'type'  => 'raw',
			'value' => '$data->senderid?CHtml::link("{$data->sender} [{$data->senderid}]", array("/admin/players/update", "id" => $data->senderid)):"$data->sender"'
		],
		'owner'=> [
			'name'  => 'owner',
			'type'  => 'raw',
			'value' => '$data->owner?CHtml::link("{$data->ownerModel->user} [{$data->owner}]", array("/admin/players/update", "id" => $data->owner)):""'
		],
		'subject',
		'body'=> [
			'name'  => 'body',
			'type'  => 'raw',
			'value' => '$data->body'
		],
		'unread'=>[
			'name'        => 'unread',
			'filter'      => CHtml::dropDownList( 'Mail[unread]', $model->unread,
				[null => '-'] + $model->unreadTypes
			),
			'value' => '$data->getUnreadName()'
		],
		'kbox'=>[
			'name'        => 'kbox',
			'filter'      => CHtml::dropDownList( 'Mail[kbox]', $model->unread,
				[null => '-'] + $model->kboxTypes
			),
			'value' => '$data->getKboxName()'
		],
		//'senderdel',
		//'ownerdel',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template' => '{delete}'
		)
),
)); ?>
