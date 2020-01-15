<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AjaxModuleController extends ModuleController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/ajax';

  public function init() {}

  protected function beforeAction($action){
    if (Yii::app()->request->isAjaxRequest){
      return parent::beforeAction($action);
    } else {
      $this->redirect('/');
      return false;
    }
  }
}