<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class ModuleController extends Controller
{
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


    public function init()
    {
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/css/jui/jquery-ui-1.9.2.custom.css'
            )
        );

        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->getClientScript()->registerCoreScript('jquery.ui');

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/js/frame.js'
            )
        );
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . '/js/components/jquery.history.js'
            )
        );
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


        //parent::init();
    }


    protected function beforeAction($action)
    {
        $locationControllers = array('mine');

        if (in_array(Yii::app()->controller->module->id, $locationControllers))
        {

            if (!Yii::app()->stat->travel_place)
            {
                $this->redirect('/city.php');
                Yii::app()->end();
            }
            elseif (Yii::app()->stat->travel_place != '/caves.php')
            {
                $this->redirect(Yii::app()->stat->travel_place);
                Yii::app()->end();
            }
        }

        return parent::beforeAction($action);
    }

}