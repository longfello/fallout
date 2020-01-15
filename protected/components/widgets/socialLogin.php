<?php

  class socialLogin extends CWidget {
    public $layout  = 'home';

    function run(){
	    $config_file_path = Yii::getPathOfAlias('application.components').'/hybridauth/hybridauth/config.php';
	    require_once( Yii::getPathOfAlias('application.components') . "/hybridauth/hybridauth/Hybrid/Auth.php" );
	    $hybridauth = new Hybrid_Auth( $config_file_path );

		$this->render('socialLogin/'.$this->layout, array(
			'auth' => $hybridauth
		));
    }
  }