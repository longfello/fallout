<?php

class TranslateModule extends CWebModule
{
  public $preload = array(
      'booster'
  );

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'translate.models.*',
			'translate.components.*',
			'application.modules.admin.components.*',
		));
		Yii::app()->theme = 'adminLTE';
		Yii::app()->setLanguage('ru');
    Yii::app()->getComponent('booster')->init();
		Yii::app()->getModule('admin');
	}
}
