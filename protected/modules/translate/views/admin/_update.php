<?php
/* @var $this AdminController */
/* @var $model LanguageTranslate */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'language-translate-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
  <script src='/js/components/ckeditor-admin/ckeditor.js'></script>
  <script type='text/javascript'>
    $(document).ready(function(){
      $('.turn-on-editor').on('click', function(e){
        e.preventDefault();
        $(this).hide();
        CKEDITOR.replace( $(this).data('href'), {
          toolbar: 'Full',
          enterMode : CKEDITOR.ENTER_BR,
          shiftEnterMode: CKEDITOR.ENTER_P
        });
      });
    });
  </script>

	<?php echo $form->errorSummary($models); ?>

  <?php foreach($all_languages as $language): ?>
    <?php $model = $models[$language->slug]; ?>
    <div class="row">
      <label><?= $language->name ?>
      <?php if (isset($languages[$language->slug])) {?>
        <a href="#" data-href="LanguageTranslate_<?=$language->slug?>_value" class="turn-on-editor">ред.</a></label>
        <?php echo $form->textArea($model, "[$language->slug]value", array('style' => 'width:100%;','rows'=>7)); ?>
      <?php } else { ?>
          </label>
        <?php echo $form->textArea($model, "[$language->slug]value", array('style' => 'width:100%;','rows'=>7, 'readonly' => 'readonly')); ?>
      <?php } ?>
      <?php echo $form->error($model,'value'); ?>
    </div>
  <?php endforeach ?>

	<div class="row buttons">
    <br>
		<?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
	</div>

  <div style="background: #EEE; border-radius: 10px; padding:15px; margin: 20px 0; font-style: italic; ">
    <h3>Замечания</h3>
    <ul>
      <li>Если в строке встречается знак %s - это место для подстановки подстроки. В данном случае текст не может содержать знак процента (%) - вместо него следует использовать знак двойного процента (%%).</li>
      <li>Количество мест подстановки (%s) не может быть отличным от исходного - это приведет к ошибке.</li>
      <li>Нажатие на ссылку ред. добавить визуальный редактор.</li>
    </ul>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->