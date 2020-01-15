<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class GameController extends Controller
{
    protected function beforeAction($action)
    {
      if (!Yii::app()->stat->id){
	      $this->redirect('/');
      }
      return parent::beforeAction($action);
    }
}