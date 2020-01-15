<?php
/* @var $this PlayersController */
/* @var $model GiftsForm  */
/* @var $form TbActiveForm */

$this->breadcrumbs = array(
    'Игроки' => array('index'),
    'Выдача предметов',
);

$count = Gifts::model()->count();
$info  = $count?['label' => '<div class="alert-info" title="Предметы выдаются в фоновом режиме">В очереди на выдачу: '.$count.'</div>']:['label' => '<div class="alert-success">Всё выдано</div>'];

$this->menu = array(
    array('label' => 'Перечень игроков', 'url' => array('index')),
    $info
);
?>

<?php $form = $this->beginWidget('MLActiveForm', array(
    //'id'=>'login-form',
    'action' => CController::createUrl('players/gifts'),
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
<?php
$form->errorSummary($model);
?>

<?php echo $form->labelEx($model, 'type') ?>
<?php echo $form->hiddenField($model, 'type') ?>
<ul class="nav nav-tabs gift-type" role="tablist">
    <?php $first = true; ?>
    <?php foreach ($model->available_types as $slug => $name ) { ?>
        <li role="presentation" <?= $first?'class="active"':'';?>><a href="#sendtype-<?=$slug?>" aria-controls="sendtype-<?=$slug?>" role="tab" data-toggle="tab" data-type="<?=$slug?>"><?=$name?></a></li>
        <?php $first = false; ?>
    <?php } ?>
</ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <?php $first = true; ?>
        <?php foreach ($model->available_types as $slug => $name ) { ?>
            <div role="tabpanel" class="tab-pane <?= $first?'active':'';?>" id="sendtype-<?=$slug?>">
                <div class="well">
                    <?php $this->renderPartial('mail/_type_'.$slug, array('model' => $model, 'form' => $form)); ?>
                </div>
            </div>
            <?php $first = false; ?>
        <?php } ?>
    </div>

    <div id="products_block">
    <table class="table table-bordered table-responsive table-products">
        <tr>
            <th>Предмет</th>
            <th>Количество</th>
            <th></th>
        </tr>
        <?php foreach($model->item as $key=>$item){
            if ($item>0) { ?>
                <tr>
                    <td>
                        <?php
                        $equipment = Equipment::model()->findByPk($item);
                        echo $equipment->name ?>
                        <?= $form->hiddenField($model, 'item[]', ['value' => $item]) ?>
                    </td>
                    <td><?= $form->numberField($model, 'count[]', ['value' => (isset($model->count[$key])?$model->count[$key]:1)]) ?></td>
                    <td><a class="btn btn-danger btn-remove"><i class="fa fa-remove"></i> Удалить</a></td>
                </tr>
            <?php }
        } ?>
    </table>

    <div class="well add-equipment-wrapper">
        <h2>Добавить предмет</h2>
        <?php echo $form->autocompleteInput($model,'equipment_id', 'Equipment', 'id', 'name', ['widgetOptions'=>['htmlOptions' => ['class' => 'add-drop-equipment', 'name' => 'crafting-equipment']]]); ?>
        <a class="btn btn-success btn-add"><i class="fa fa-plus"></i> Добавить</a>
        <table class="hidden template">
            <tr>
                <td><span class="name"></span><?= $form->hiddenField($model, 'item[]', ['value' => 0]) ?></td>
                <td><?= $form->numberField($model, 'count[]', ['value' => 1]) ?></td>
                <td><a class="btn btn-danger btn-remove"><i class="fa fa-remove"></i> Удалить</a></td>
            </tr>
        </table>
    </div>

    <?php echo $form->labelEx($model, 'text') ?>
    <?php echo $this->widget('bootstrap.widgets.TbCKEditor',
        array(
            'model'     => $model,
            'attribute' => 'text'
        ), true);
    ?>
    <?php echo $form->error($model, 'text') ?>

    <?php echo $form->labelEx($model, 'text_en') ?>
    <?php echo $this->widget('bootstrap.widgets.TbCKEditor',
        array(
            'model'     => $model,
            'attribute' => 'text_en'
        ), true);
    ?>
    <?php echo $form->error($model, 'text_en') ?>

    <?php echo $form->numberFieldGroup($model, 'napad'); ?>
    <?php echo $form->numberFieldGroup($model, 'pohod'); ?>
    <?php echo $form->numberFieldGroup($model, 'pleft'); ?>
    <div class="footer">
        <?php echo CHtml::submitButton('Выдать', array('class' => 'btn bg-blue btn-block')); ?>
    </div>

<?php $this->endWidget(); ?>

<?php

Yii::app()->clientScript->registerScript("gifitng", "
$('.gift-type a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) {
  $('#GiftsForm_type').val($(e.target).data('type'));
});
  ", CClientScript::POS_READY);

Yii::app()->clientScript->registerScript("products-form", "
$(document).on('click', '.table-products a.btn-remove', function(e){
  e.preventDefault();
  $(this).parents('tr').remove();
});

$('.add-equipment-wrapper a.btn-add').on('click', function(e){
  e.preventDefault();
  var template = $('.add-equipment-wrapper .template tr').clone(true);
    console.log(template);
  var textElCopy = $('.add-equipment-wrapper .input-group-addon .items-list li:first').clone();
  textElCopy.find('a').remove();
  var text = textElCopy.text();
  
  $(template).find('.name').html(text);
  $(template).find('#GiftsForm_item').val($('.add-equipment-wrapper #crafting-equipment').val());
  console.log(template);
  $('.table-products').append(template);
});

", CClientScript::POS_READY);