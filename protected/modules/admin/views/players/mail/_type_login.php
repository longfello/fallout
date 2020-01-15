<?php
/* @var $model MailForm  */
/* @var $form TbActiveForm */
?>

<?php
$this->widget( 'booster.widgets.TbDatePicker',
    array('model'=>$model,
        'attribute'=>'start',
        'options' => array(
            'language' => 'ru',
            'format' => 'yyyy-mm-dd',
            'autoclose' => 'true',
        ),
        'htmlOptions' => array('class'=>'col-md-4'),
    )
);
?>
<div class="col-md-1 text-center">-</div>
<?php
$this->widget( 'booster.widgets.TbDatePicker',
    array('model'=>$model,
        'attribute'=>'end',
        'options' => array(
            'language' => 'ru',
            'format' => 'yyyy-mm-dd',
            'autoclose' => 'true',
        ),
        'htmlOptions' => array('class'=>'col-md-4'),
    )
);
?>

<div class="clearfix"></div>