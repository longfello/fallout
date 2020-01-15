<?php
$this->breadcrumbs=array(
    'Приглашения'=>array('index')
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('invite-log-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="<?= Yii::app()->createUrl('/admin/inviteLog'); ?>">Отправленные на email</a></li>
    <li><a href="<?= Yii::app()->createUrl('/admin/inviteSoc'); ?>">Переходы с соц.сетей</a></li>
</ul>

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

<?php
$this->widget('booster.widgets.TbExtendedGridView',array(
    'id'=>'invite-log-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'created_at',
        'owner'=> [
            'name'  => 'owner',
            'type'  => 'raw',
            'value' => '$data->owner?CHtml::link("{$data->ownerModel->user} [{$data->owner}]", array("/admin/players/update", "id" => $data->owner)):""'
        ],
        'email',
        'caps_send',
        'caps_reg',
        'unsubscribed' => [
            'name'  => 'unsubscribed',
            'type'  => 'raw',
            'value' => '$data->unsubscribed?"Да":"Нет"',
            'filter'      => CHtml::dropDownList( 'InviteLog[unsubscribed]', $model->unsubscribed,
                [null => '-', '0'=>'Нет', '1'=>'Да']
            ),
        ]
    ),
    'extendedSummary' => array(
        'title' => 'Всего',
        'columns' => array(
            'caps_send' => array('label'=>'Крышек за отправку', 'class'=>'TbSumOperation'),
            'caps_reg' => array('label'=>'Крышек за регистрацию', 'class'=>'TbSumOperation'),
        )
    )
)); ?>
