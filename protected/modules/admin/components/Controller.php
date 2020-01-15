<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
  /**
   * @var string the default layout for the controller view. Defaults to '//layouts/column1',
   * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
   */
  public $layout = '//layouts/column1';
  /**
   * @var array context menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $menu = array();
  /**
   * @var array the breadcrumbs of the current page. The value of this property will
   * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
   * for more details on how to specify this property.
   */
  public $breadcrumbs = array();

  public function init()
  {
    Yii::app()->getClientScript()->registerCoreScript('jquery');
  }

	public function renderPopup($view, $params=[], $header=null, $footer=null){
		header('Content-type: application/json');
		echo CJSON::encode([
			'content' => $this->renderPartial($view, $params, true),
			'title'   => $header,
			'buttons' => $footer
		]);
	    foreach (Yii::app()->log->routes as $route) {
		    if($route instanceof CWebLogRoute) {
			    $route->enabled = false; // disable any weblogroutes
		    }
	    }
	    Yii::app()->end();
	}
}