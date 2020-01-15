<?php
/**
 * @var $form MLActiveForm
 * @var $model Crafting
 */

  $form=$this->beginWidget('MLActiveForm',array(
	'id'=>'crafting-form',
	'enableAjaxValidation'=>false,
));
  ?>

<?php echo $form->errorSummary($model); ?>

<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#p1" aria-controls="p1" role="tab" data-toggle="tab">Основное</a></li>
    <li role="presentation"><a href="#p2" aria-controls="p2" role="tab" data-toggle="tab">Элементы</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="p1">
      <div class="row well">
        <div class="col-xs-12">
	        <?php echo $form->MLtextFieldGroup($model,'name','name'); ?>
	        <?php echo $form->dropDownListGroup($model,'type',array('widgetOptions'=>array('data'=>$model->availableProfessions, 'htmlOptions'=>array('class'=>'span5','maxlength'=>1)))); ?>
	        <?php echo $form->numberFieldGroup($model,'minlev',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'maxlev',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'minpro',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'maxpro',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'energy',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'chance',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'chancepp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'toprand',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
          <?php echo $form->autocompleteInput($model,'success_item','Equipment', 'id', 'name', [], 1); ?>
	        <?php echo $form->numberFieldGroup($model,'success_item_count',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->MLtextAreaVisualGroup($model,'fail_text','fail_text'); ?>
          <?php echo $form->autocompleteInput($model,'fail_item','Equipment', 'id', 'name', [], 1); ?>
	        <?php echo $form->numberFieldGroup($model,'fail_item_count',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->numberFieldGroup($model,'max_exp_prolvl',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="p2">
      <div class="row well">
        <div class="col-xs-12">
          <table class="table table-bordered table-responsive table-crafting-drop">
            <tr>
              <th>Предмет</th>
              <th>Количество</th>
              <th>Изчезает?</th>
              <th></th>
            </tr>
	          <?php foreach($model->items as $item){ ?>
                <tr>
                  <td><?= $item->equipment->name ?><?= $form->hiddenField($item, 'item[]', ['value' => $item->item]) ?></td>
                  <td><?= $form->numberField($item, 'count[]', ['value' => $item->count]) ?></td>
                  <td><?= $form->dropDownList($item, 'disappear[]', ['N' => 'Нет', 'Y' => 'Да'], ['value' => $item->disappear]) ?></td>
                  <td><a class="btn btn-danger btn-remove"><i class="fa fa-remove"></i> Удалить</a></td>
                </tr>
	          <?php } ?>
          </table>

          <div class="well add-crafting-equipment-wrapper">
            <h2>Добавить предмет</h2>
	          <?php $ac = new CraftingItems(); ?>
	          <?php echo $form->autocompleteInput($ac,'item', 'Equipment', 'id', 'name', ['widgetOptions'=>['htmlOptions' => ['class' => 'add-drop-equipment', 'name' => 'crafting-equipment']]]); ?>
            <a class="btn btn-success btn-add"><i class="fa fa-plus"></i> Добавить</a>

            <table class="hidden template">
              <tr>
                <td><span class="name"></span><?= $form->hiddenField($ac, 'item[]', ['value' => 0]) ?></td>
                <td><?= $form->numberField($ac, 'count[]', ['value' => $ac->count]) ?></td>
                <td><?= $form->dropDownList($ac, 'disappear[]', ['N' => 'Нет', 'Y' => 'Да'], ['value' => $ac->disappear]) ?></td>
                <td><a class="btn btn-danger btn-remove"><i class="fa fa-remove"></i> Удалить</a></td>
              </tr>
            </table>
          </div>
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

<?php

Yii::app()->clientScript->registerScript("crafting-form", "
$(document).on('click', '.table-crafting-drop a.btn-remove', function(e){
  e.preventDefault();
  $(this).parents('tr').remove();
});

$('.add-crafting-equipment-wrapper a.btn-add').on('click', function(e){
  e.preventDefault();
  var template = $('.add-crafting-equipment-wrapper .template tr').clone(true);
  
  var textElCopy = $('.add-crafting-equipment-wrapper .input-group-addon .items-list li:first').clone();
  textElCopy.find('a').remove();
  var text = textElCopy.text();
  
  $(template).find('.name').html(text);
  $(template).find('#CraftingItems_item').val($('.add-crafting-equipment-wrapper #crafting-equipment').val());
  $('.table-crafting-drop').append(template);
});

", CClientScript::POS_READY);

?>
