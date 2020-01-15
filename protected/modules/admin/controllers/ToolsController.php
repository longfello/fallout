<?php

class ToolsController extends Controller
{
  public $pageTitle = 'Инструменты';
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array(
				'allow',  // allow all users to perform 'index' and 'view' actions
				'actions' => array('manager'),
				'roles' => array('admin'),
			),
			array(
				'deny',  // deny all users
				'users' => array( '*' ),
			),
		);
	}

  public function actionManager()
  {
	  Yii::app()->clientScript->registerCss('iframeBorder', 'iframe {border:none; }');
	  Yii::app()->clientScript->registerScript('iframe',"
			resizeIframe();
			
			$(window).resize(function(){
				resizeIframe();
			});
			
			function resizeIframe()
			{
				$('iframe').attr('width',$('#iframe-div').width());
				$('iframe').attr('height','700');			
			}
		");
    $this->render('manager');
  }

}