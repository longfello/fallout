<?php

  class Activation extends CWidget {
  	public $email;

    function run(){
//      $js  = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'js');

//      Yii::app()->clientScript->registerCssFile($js.'/components/fancyBox/source/jquery.fancybox.css');
//      Yii::app()->clientScript->registerScriptFile($js.'/components/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js');
//      Yii::app()->clientScript->registerScriptFile($js.'/components/fancyBox/source/jquery.fancybox.pack.js');

	    $this->email = $this->email?$this->email:Yii::app()->stat->model->email;
        $this->render('Activation/index', ['email' => $this->email]);
    }
  }