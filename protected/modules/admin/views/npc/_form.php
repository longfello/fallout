<?php
  /**
   * @var $form MLActiveForm
   * @var $model Npc
   * @var $this Controller
   */

  $form=$this->beginWidget('MLActiveForm',array(
	  'id'=>'npc-form',
	  'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data')
  ));
?>

<p class="help-block">Поля с пометкой <span class="required">*</span> обязательные для заполнения.</p>

  <?php echo $form->errorSummary($model); ?>

<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#p1" aria-controls="p1" role="tab" data-toggle="tab">Основное</a></li>
    <li role="presentation"><a href="#p2" aria-controls="p2" role="tab" data-toggle="tab">Характеристики</a></li>
    <li role="presentation"><a href="#p3" aria-controls="p3" role="tab" data-toggle="tab">Дроп</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="p1">
      <div class="row well">
        <div class="col-xs-12">
          <div class="col-xs-2">
	          <?php
	          if ($model->pic) {
		          echo CHtml::tag('br');
		          echo CHtml::image($model->getImage(), $model->name, ['style' => 'max-width:128px;']);
		          echo CHtml::tag('br');
	          }
	          echo $form->fileFieldGroup($model, 'image_tmp');
	          ?>
          </div>
          <div class="col-xs-10">
	          <?php echo $form->MLtextFieldGroup($model,'name', 'name'); ?>
	          <?php echo $form->MLtextAreaVisualGroup($model,'desc', 'desc'); ?>
	          <?php echo $form->dropDownListGroup($model,'type',array('widgetOptions'=>array('data' =>  CHtml::listData( NpcType::model()->findAll( array( 'order' => 'name' ) ), 'id', 'name'),'htmlOptions'=>array('class'=>'span5')))); ?>
	          <?php echo $form->textFieldGroup($model,'level',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	          <?php echo $form->dropDownListGroup($model,'gender',array('widgetOptions'=>array('data'=> $model->genders, 'htmlOptions'=>array('class'=>'span5','maxlength'=>1)))); ?>
	          <?php echo $form->dropDownListGroup($model,'typeloc', array('widgetOptions'=>array('data'=> $model->locations, 'htmlOptions'=>array('class'=>'input-large')))); ?>
	          <?php echo $form->textFieldGroup($model,'gold',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	          <?php echo $form->textFieldGroup($model,'platinum',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
            <?php echo $form->dropDownListGroup($model, 'enabled', array('widgetOptions' => array('data' => array("0" => "Запрещен", "1" => "Разрешен",), 'htmlOptions' => array('class' => 'input-large')))); ?>
          </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="p2">
      <div class="row well">
        <div class="col-xs-12">
	        <?php echo $form->textFieldGroup($model,'strength',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->textFieldGroup($model,'agility',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->textFieldGroup($model,'hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->textFieldGroup($model,'max_hp',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->textFieldGroup($model,'defense',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
	        <?php echo $form->textFieldGroup($model,'ap',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="p3">
      <div class="row well">
        <div class="col-xs-12">
          <table class="table table-bordered table-responsive table-npc-drop">
            <tr>
              <th rowspan="2">Предмет</th>
              <th colspan="2">Шанс выпадения</th>
              <th colspan="2">Количество предметов</th>
              <th rowspan="2">Нотификация</th>
              <th rowspan="2"></th>
            </tr>
            <tr>
              <th>Числитель</th>
              <th>Знаменатель</th>
              <th>От</th>
              <th>До</th>
            </tr>
            <?php foreach($model->drop as $drop){ ?>
                <tr>
                  <td><?= $drop->equipment->name ?><?= $form->hiddenField($drop, 'item[]', ['value' => $drop->item]) ?></td>
                  <td><?= $form->numberField($drop, 'chance[]', ['value' => $drop->chance]) ?></td>
                  <td><?= $form->numberField($drop, 'toprand[]', ['value' => $drop->toprand]) ?></td>
                  <td><?= $form->numberField($drop, 'amin[]', ['value' => $drop->amin]) ?></td>
                  <td><?= $form->numberField($drop, 'amax[]', ['value' => $drop->amax]) ?></td>
                  <td><?= $form->dropDownList($drop, 'notify[]', ['N' => 'Нет', 'Y' => 'Да'], ['value' => $drop->notify]) ?></td>
                  <td><a class="btn btn-danger btn-remove"><i class="fa fa-remove"></i> Удалить</a></td>
                </tr>
            <?php } ?>
          </table>
          <?php ?>

          <div class="well add-drop-equipment-wrapper">
            <h2>Добавить дроп</h2>
            <?php $ac = new Npcdrop(); ?>
            <?php echo $form->autocompleteInput($ac,'item', 'Equipment', 'id', 'name', ['widgetOptions'=>['htmlOptions' => ['class' => 'add-drop-equipment', 'name' => 'npc-drop-equipment']]], -1); ?>
            <a class="btn btn-success btn-add"><i class="fa fa-plus"></i> Добавить</a>

            <table class="hidden template">
              <tr>
                <td><span class="name"></span><?= $form->hiddenField($ac, 'item[]', ['value' => 0]) ?></td>
                <td><?= $form->numberField($ac, 'chance[]', ['value' => $ac->chance]) ?></td>
                <td><?= $form->numberField($ac, 'toprand[]', ['value' => $ac->toprand]) ?></td>
                <td><?= $form->numberField($ac, 'amin[]', ['value' => $ac->amin]) ?></td>
                <td><?= $form->numberField($ac, 'amax[]', ['value' => $ac->amax]) ?></td>
                <td><?= $form->dropDownList($ac, 'notify[]', ['N' => 'Нет', 'Y' => 'Да'], ['value' => $ac->notify]) ?></td>
                <td><a class="btn btn-danger btn-remove"><i class="fa fa-remove"></i> Удалить</a></td>
              </tr>
            </table>
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

  Yii::app()->clientScript->registerScript("npc-drop-form", "
$(document).on('click', '.table-npc-drop a.btn-remove', function(e){
  e.preventDefault();
  $(this).parents('tr').remove();
});

$('.add-drop-equipment-wrapper a.btn-add').on('click', function(e){
  e.preventDefault();
  var template = $('.add-drop-equipment-wrapper .template tr').clone(true);
  
  var textElCopy = $('.add-drop-equipment-wrapper .input-group-addon .items-list li:first').clone();
  textElCopy.find('a').remove();
  var text = textElCopy.text();
  
  $(template).find('.name').html(text);
  $(template).find('#Npcdrop_item').val($('.add-drop-equipment-wrapper #npc-drop-equipment').val());
  $('.table-npc-drop').append(template);
});

", CClientScript::POS_READY);

?>