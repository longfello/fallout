<?php

  class PhotoVideo extends CWidget {
    public $layout  = 'home';

    function run(){
      $js  = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'js');

      Yii::app()->clientScript->registerCssFile($js.'/components/fancyBox/source/jquery.fancybox.css');
      Yii::app()->clientScript->registerScriptFile($js.'/components/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js');
      Yii::app()->clientScript->registerScriptFile($js.'/components/fancyBox/source/jquery.fancybox.pack.js');

      $this->render('PhotoVideo/'.$this->layout, array());
    }
  }