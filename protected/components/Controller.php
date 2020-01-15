<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
  public $langModel = t::MODEL_GAME;
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/base';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

  public $metaDescription = '';
  public $metaKeywords    = '';


    public function init()
    {
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->clientScript->getCoreScriptUrl().
            '/jui/css/base/jquery-ui.css'
        );


        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->getClientScript()->registerCoreScript('jquery.ui');

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/js/frame.js'
            )
        );
/*
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/js/components/jquery.history.js'
            )
        );
*/
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/js/system.js'
            )
        );


        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/js/counter.js'
            )
        );


        parent::init();
    }


    protected function beforeAction($action)
    {
      Ban::check();
      return parent::beforeAction($action);
    }


    protected function afterAction($action)
    {

    }

}