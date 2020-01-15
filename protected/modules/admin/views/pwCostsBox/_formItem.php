<?php
/**
 * @var $form TbActiveForm
 * @var $model PwCostsContentItems
 */
?>

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'pw-costs-content-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['class' => 'esdPopup']
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->hiddenField($model,'content_id'); ?>

<?php echo $form->textFieldGroup($model,'name',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>255)))); ?>

<?php echo $form->textFieldGroup($model,'chance',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<?php echo $form->dropDownListGroup($model,'count_rule', array(
	'widgetOptions'=>array(
		'data' => array(
			'exact'   => 'Как указано',
			'max'     => 'Выбрать случайно (до)',
		),
		'htmlOptions'=>array('class'=>'span5')),
	'hint'=>'Как указано - будет выдано точно указанное количество'
)); ?>

<?php echo $form->numberFieldGroup($model,'count',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

<div class="equipmentGroup">
<?php echo $form->dropDownListGroup($model,'rule', array(
	'widgetOptions'=>array(
		'data' => array(
			'in'    => 'Любой из списка',
			'any'   => 'Любой',
		),
		'htmlOptions'=>array('class'=>'span5')),
    	'hint'=>'Любой - будет выдан случайный предмет из указанной категории. Любой из списка - будет выдан случайный предмет из приведенного списка.'

)); ?>
</div>

<?php echo $form->dropDownListGroup($model,'type', array(
	'widgetOptions'=>array(
		'data' => array(
			'weapon'    => 'Оружие',
			'armor'     => 'Броня',
			'equipment' => 'Оборудование',
			'food'      => 'Еда',
			'gold'      => 'Золото',
			'platinum'  => 'Крышки',
			'energy'    => 'Энергетики',
		),
		'htmlOptions'=>array('class'=>'span5'))
)); ?>

<div class="form-group equipmentGroup">
	<?= $form->labelEx($model, 'list') ?>
	<input type="text" class="span5 autocomplete equipmentAutocomplete">
	<ul class="equipmentItemsList">
		<?php
		  foreach ($model->getListItems() as $one) {
			  ?>
			    <li data-id="<?= $one->id ?>"><?= $one->type ?>) <?= $one->name ?> [<?= $one->id ?>]<a class='remove btn btn-danger btn-xs'>X</a></li>
			  <?php
		  }
		?>
	</ul>
</div>
<?php echo $form->hiddenField($model,'list', array('id'=>'equipmentList')); ?>

<?php $this->endWidget(); ?>
