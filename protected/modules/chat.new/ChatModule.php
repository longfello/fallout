<?php

class ChatModule extends CWebModule
{
	private $_assetsUrl;

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'chat.models.*',
			'chat.components.*',
		));

		$this->registerAssets();

	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
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
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/font-awesome.min.css');
//    Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/daterangepicker/daterangepicker-bs3.css');
//    Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/plugins/daterangepicker/daterangepicker.js', CClientScript::POS_END);
	}

}
