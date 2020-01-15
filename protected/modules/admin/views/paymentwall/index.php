<?php
$this->breadcrumbs = array(
	'Оплаты'
);

Yii::app()->clientScript->registerScript( 'search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('paymentwall-grid', {
data: $(this).serialize()
});
return false;
});
" );
?>

<?php echo CHtml::link( 'Расширенный поиск', '#', array( 'class' => 'search-button btn' ) ); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial( '_search', array(
		'model' => $model,
	) ); ?>
</div><!-- search-form -->

<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'paymentwall-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'order_id',
		'amount',
		'user_id',
		'ref',
		'pay_time',
	),
) ); ?>
