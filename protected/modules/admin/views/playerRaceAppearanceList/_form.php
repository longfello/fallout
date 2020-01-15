<?php
  /**
   * @var $model PlayerRaceAppearanceList
   * @var $form TbActiveForm
   */
?>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'player-race-appearance-list-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => ['enctype' => 'multipart/form-data']
)); ?>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->dropDownListGroup($model,'gender', array('widgetOptions'=>array('data'=>array("male"=>"Мужской","female"=>"Женский",), 'htmlOptions'=>array('class'=>'input-large')))); ?>
<?php echo $form->dropDownListGroup($model,'appearance_layout_id',array('widgetOptions'=>array('data' => CHtml::listData(AppearanceLayout::model()->findAll(), 'id', 'name'), 'htmlOptions'=>array('class'=>'span5')))); ?>
<?php echo $form->dropDownListGroup($model,'default_layout',array('widgetOptions'=>array('data' => [0 => 'Нет', 1=>'Да'], 'htmlOptions'=>array('class'=>'span5')))); ?>

<?php
    if (!$model->isNewRecord) {
	    $layouts = explode(',',$model->layout->layouts);
	    foreach ($layouts as $layout) {
		    $layout_name = ($layout == 'layout')?"Основной слой":"Дополнительный слой - ".$layout;
		    $id   = 'PlayerRaceAppearanceList_image_'.$layout;
		    $name = 'PlayerRaceAppearanceList[image]['.$layout.']';
		    ?>
			    <div class="form-group">
				    <label class="control-label" for="<?= $id ?>"><?= $layout_name ?></label>
				    <input id="yt<?= $id ?>" value="" name="<?= $name ?>" type="hidden">
				    <input class="form-control" placeholder="<?= $layout_name ?>" name="<?= $name ?>" id="<?= $id ?>" type="file">
			    </div>
		    <?php
		    echo CHtml::image($model->getPicture($layout), '', ['style' => 'max-height:100px;max-width:300px;']);
		    echo CHtml::tag('br');
		    echo CHtml::tag('br');
	    }
    }
?>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
