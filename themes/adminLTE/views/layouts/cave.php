<?php $this->beginContent('//layouts/column2'); ?>
<?php /* @var $this Controller */ ?>
<?php
  Yii::app()->clientScript->registerCssFile("/css/bootstrap-modal.css");
  Yii::app()->clientScript->registerCssFile("/css/bootstrap-modal-bs3patch.css");
  Yii::app()->clientScript->registerCssFile("/css/mainrev.css");
  Yii::app()->clientScript->registerScriptFile("/js/upd/bootstrap-modal.js", CClientScript::POS_END);
  Yii::app()->clientScript->registerScriptFile("/js/upd/bootstrap-modalmanager.js", CClientScript::POS_END);
  Yii::app()->clientScript->registerScriptFile("/js/upd/jquery.form.min.js", CClientScript::POS_END);
  Yii::app()->clientScript->registerScriptFile("/js/upd/mainrev.js", CClientScript::POS_END);
  Yii::app()->clientScript->registerScript("cave-map-wrapper", "

$('.right-side > .content').css('padding', 0);
resize_map_wrapper();
$(window).on('resize', resize_map_wrapper);

function resize_map_wrapper(){
  $('.cave-map-wrapper').css('height', 0);
  $('.cave-map-wrapper').css('height', $('.wrapper').outerHeight() - $('.content-header').outerHeight());
}
", CClientScript::POS_READY);
?>
<div class="cave-map-wrapper">
  <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>
