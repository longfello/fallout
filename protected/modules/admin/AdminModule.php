<?php

class AdminModule extends CWebModule
{
  private $_assetsUrl;
  private $_ownAssetsUrl;
  public $preload = array('booster');

  public function init()
  {
    if (!isset($_SESSION['userid']))  Yii::app()->request->redirect('/');
	Yii::app()->clientScript->combineScriptFiles = false;
	Yii::app()->clientScript->combineCssFiles = false;
	Yii::app()->clientScript->optimizeScriptFiles = false;
	Yii::app()->clientScript->optimizeCssFiles = false;


	  $this->registerAssets();
    Yii::app()->name = Yii::t('app', "Administration");
    // this method is called when the module is being created
    // you may place code here to customize the module or the application
    // import the module-level models and components
    $this->setImport(array(
        'admin.models.*',
        'admin.components.*',
        'admin.extensions.*'
    ));

    Yii::app()->theme = "adminLTE";
    Yii::app()->setLanguage('ru');
    Yii::app()->getComponent('booster')->init();
  }

  /**
   * @return string base URL that contains all published asset files of this module.
   */
  public function getAssetsUrl()
  {
    if ($this->_assetsUrl == null) {
	    $this->_assetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('webroot.themes.adminLTE')
          // Comment the line below out in production.
          , false, -1, true
      );
    }
    return $this->_assetsUrl;
  }

  public function getOwnAssetsUrl()
  {
    if ($this->_ownAssetsUrl == null) {
       $this->_ownAssetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('admin').'/assets'
          // Comment the line below out in production.
          , false, -1, YII_DEBUG
      );
    }
    return $this->_ownAssetsUrl;
  }

  /**
   * Register the CSS and JS files for the module
   */
  public function registerAssets()
  {
	  Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
      Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl() .
        '/jui/css/base/jquery-ui.css'
      );

      Yii::app()->getClientScript()->registerScriptFile($this->getOwnAssetsUrl().'/js/esd-system.js');
	  Yii::app()->getClientScript()->registerScriptFile($this->getOwnAssetsUrl().'/js/preloader.js');
	  Yii::app()->getClientScript()->registerScriptFile($this->getOwnAssetsUrl().'/js/popup.js');
	  Yii::app()->getClientScript()->registerCssFile($this->getOwnAssetsUrl().'/css/preloader.css');

  }

  public function beforeControllerAction($controller, $action)
  {
	  $criteria = new CDbCriteria();
	  $criteria->addCondition("'".Ban::getIP()."' LIKE ip");
	  if (!AdminsIp::model()->findAll($criteria)){
//  		throw new CHttpException(404,'The specified page cannot be found.');
	  }

    if (parent::beforeControllerAction($controller, $action)) {
      // this method is called before any module controller action is performed
      // you may place customized code here
      Yii::app()->widgetFactory->widgets['CBreadcrumbs'] = array('homeLink' => CHtml::link(Yii::t(Yii::app()->language, 'Home'), array('/admin')));

      $route = $controller->id . '/' . $action->id;
      // echo $route;
      $publicPages = array(
          'default/login',
          'default/error',
          '/admin/default/login',
          '/admin/default/error',
          'user/login',
          'user/error',
      );
      if (Yii::app()->user->isGuest && !in_array($route, $publicPages)) {
        $return = Yii::app()->request->url;
        if (strpos($return,'/admin') === FALSE ) $return = '/admin';
        Yii::app()->session['referrer'] = $return;

        $controller->redirect('/admin/default/login');
        //Yii::app()->getModule('admin')->user->loginRequired();
      } else {
	      t::getInstance()->setLanguage('ru');
	      return true;
      }
    } else
      return false;
  }
}
