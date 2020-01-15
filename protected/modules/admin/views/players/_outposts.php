<?php
/**
 *
 * @var Players $model
 * @var Outposts $outpost
 * @var TbActiveForm $form
 */

$outpost = $model->outpost;
?>

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'outposts-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php if ($outpost){ ?>
  <?php echo $form->errorSummary($outpost); ?>

  <?php echo $form->numberFieldGroup($outpost,'size',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

  <?php echo $form->numberFieldGroup($outpost,'mines',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

  <?php echo $form->numberFieldGroup($outpost,'turns',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

  <?php echo $form->numberFieldGroup($outpost,'tokens',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>20)))); ?>

  <?php echo $form->numberFieldGroup($outpost,'troops',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

  <?php echo $form->numberFieldGroup($outpost,'barricades',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

  <div class="form-actions">
    <?php $this->widget('booster.widgets.TbButton', array(
      'buttonType'=>'submit',
      'context'=>'primary',
      'label'=>'Сохранить',
	    'htmlOptions' => [
	      'name'=>'action',
	      'value'=>'update',
      ],
    )); ?>
    <?php $this->widget('booster.widgets.TbButton', array(
      'buttonType'=>'submit',
      'context'=>'primary',
      'label'=>'Удалить дом',
	    'htmlOptions' => [
	      'name'=>'action',
	      'value'=>'remove-outpost',
	      'class'=>'btn-danger',
        'confirm'=>'Вы уверены?'
      ],
    )); ?>
  </div>

<?php } else { ?>
  <div class="form-actions">
    <?php
      echo( CHtml::hiddenField('create-outpost', 1));
    ?>
	  <?php $this->widget('booster.widgets.TbButton', array(
		  'buttonType'=>'submit',
		  'context'=>'primary',
		  'label'=>'Создать дом',
	  )); ?>
  </div>
<?php } ?>
<?php $this->endWidget(); ?>
