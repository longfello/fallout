<?php
/**
 * @var $this Controller
 * @var $form MLActiveForm
 * @var $model NpcDropForm
 * @var $removeModel NpcDropRemoveForm
 */

$this->breadcrumbs = array(
	'Мобы' => array( 'index' ),
	'Массовое управление дропом',
);

$this->menu = array(
	array( 'label' => 'Перечень', 'url' => array( 'index' ) ),
	array( 'label' => 'Типы мобов', 'url' => array( '/admin/npcType/index' ) ),
);
?>

<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) {
	?>
  <div class="alert alert-<?= $key ?>"><?= $message ?></div>
  <?php
}
?>

<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#p1" aria-controls="p1" role="tab" data-toggle="tab">Добавить предмет</a></li>
    <li role="presentation"><a href="#p2" aria-controls="p2" role="tab" data-toggle="tab">Изьять предмет</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active npc-add-drop" id="p1">
      <div class="row well">
        <div class="col-xs-12">
	        <?php $form=$this->beginWidget('MLActiveForm',array(
		        'id'=>'npc-drop-form',
		        'enableAjaxValidation'=>true,
            'type' => TbActiveForm::TYPE_VERTICAL
	        )); ?>
            <div class="container-fluid ">
	            <?php echo $form->errorSummary($model); ?>
	            <?php echo $form->autocompleteInput($model,'equipment_id', 'Equipment', 'id', 'name'); ?>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12"><label>с вероятностью выпадения</label></div>
                </div>
                <div class="row">
                  <div class="col-lg-5"><?= $form->textField($model, 'numFrom', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('numFrom')]) ?></div>
                  <div class="col-lg-2 text-center">-</div>
                  <div class="col-lg-5"><?= $form->textField($model, 'numTo', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('numTo')]) ?></div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <hr>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-5"><?= $form->textField($model, 'deFrom', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('deFrom')]) ?></div>
                  <div class="col-lg-2 text-center">-</div>
                  <div class="col-lg-5"><?= $form->textField($model, 'deTo', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('deTo')]) ?></div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12"><label>в кол-ве</label></div>
                </div>
                <div class="row">
                  <div class="col-lg-5"><?= $form->textField($model, 'qFrom', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('qFrom')]) ?></div>
                  <div class="col-lg-2 text-center"> -</div>
                  <div class="col-lg-5"><?= $form->textField($model, 'qTo', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('qTo')]) ?></div>
                </div>
              </div>
	            <?php echo $form->autocompleteInput($model,'npc_ids', 'Npc', 'id', 'name', [], 255, 1); ?>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                  </div>
                </div>
              </div>
            </div>
	        <?php $this->endWidget(); ?>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane npc-remove-drop" id="p2">
      <div class="row well">
        <div class="col-xs-12">
	        <?php $form=$this->beginWidget('MLActiveForm',array(
		        'id'=>'npc-drop-remove-form',
		        'enableAjaxValidation'=>true,
		        'type' => TbActiveForm::TYPE_VERTICAL,
            'action' => '/admin/npc/drop#p2'
	        )); ?>
            <div class="container-fluid ">
	            <?php echo $form->errorSummary($removeModel); ?>
	            <?php echo $form->autocompleteInput($removeModel,'equipment_id', 'Equipment', 'id', 'name'); ?>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">Изьять</button>
                  </div>
                </div>
              </div>
            </div>
	        <?php $this->endWidget(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

Yii::app()->clientScript->registerScript('bs_tabs_hash_fix', "
var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href=\"#' + url.split('#')[1] + '\"]').tab('show');
} 
", CClientScript::POS_READY);

