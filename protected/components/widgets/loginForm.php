<?php

  class loginForm extends CWidget {
    public $layout  = 'home';
    public $visible = true;

    function run(){

      $this->render('loginForm/'.$this->layout, array(
//        'visible' => $
      ));
    }
  }