<?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
)); ?>

<?php echo $form->textFieldGroup($model, 'id', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->textFieldGroup($model, 'title', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 255)))); ?>

<?php echo $form->textFieldGroup($model, 'description', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5', 'maxlength' => 255)))); ?>

<?php echo $form->textAreaGroup($model, 'title_news', array('widgetOptions' => array('htmlOptions' => array('rows' => 6, 'cols' => 50, 'class' => 'span8')))); ?>

<?php echo $form->textAreaGroup($model, 'news', array('widgetOptions' => array('htmlOptions' => array('rows' => 6, 'cols' => 50, 'class' => 'span8')))); ?>

<?php echo $form->textFieldGroup($model, 'date', array('widgetOptions' => array('htmlOptions' => array('class' => 'span5')))); ?>

<?php echo $form->dropDownListGroup($model, 'section', array('widgetOptions' => array('data' => array("news" => "news", "article" => "article", "about" => "about", "faq" => "faq", "newuser" => "newuser", "games" => "games",), 'htmlOptions' => array('class' => 'input-large')))); ?>

<div class="form-actions">
  <?php $this->widget('booster.widgets.TbButton', array(
      'buttonType' => 'submit',
      'context' => 'primary',
      'label' => 'Поиск',
  )); ?>
</div>

<?php $this->endWidget(); ?>
