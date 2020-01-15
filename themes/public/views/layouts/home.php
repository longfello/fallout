<?php
/* @var $this Controller */
  $css = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'css');
  $js  = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'js');

//  Yii::app()->getComponent('booster')->init();
  Yii::app()->clientScript->registerCoreScript('jquery');
  Yii::app()->clientScript->registerCoreScript('jquery.ui');

  Yii::app()->clientScript->registerCssFile($css.'/jui/jquery-ui-1.9.2.custom.css');
  Yii::app()->clientScript->registerCssFile($css.'/home/bootstrap.min.css');
  Yii::app()->clientScript->registerCssFile($js.'/components/slick/slick.css');
  Yii::app()->clientScript->registerCssFile($css.'/fonts.css');
  Yii::app()->clientScript->registerCssFile($css.'/home.css');
  if (file_exists(Yii::getPathOfAlias('webroot')."/css/home_".t::iso().".css")) {
    Yii::app()->clientScript->registerCssFile($css."/home_".t::iso().".css");
  }
  Yii::app()->clientScript->registerCssFile($css.'/media.css');
  Yii::app()->clientScript->registerScriptFile($js.'/img-center.min.js');
  Yii::app()->clientScript->registerScriptFile($js.'/backgroundVideo.min.js');
  Yii::app()->clientScript->registerScriptFile($js.'/upd/bootstrap.min.js');
  Yii::app()->clientScript->registerScriptFile($js.'/components/slick/slick.min.js');
  Yii::app()->clientScript->registerScriptFile($js.'/sly.min.js');
  Yii::app()->clientScript->registerScriptFile($js.'/horizontal.js');
  Yii::app()->clientScript->registerScriptFile($js.'/home.js');
  Yii::app()->clientScript->registerScriptFile($js.'/jquery.scrollTo.min.js');
?>
<?php $this->beginContent('//layouts/home/index'); ?>
<!--<div id="content">-->
	<?php echo $content; ?>
<!--</div><!-- content -->
<?php $this->endContent(); ?>