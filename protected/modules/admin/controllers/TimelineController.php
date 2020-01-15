<?php

class TimelineController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $pageTitle = 'Хроника';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions' => array('index', 'delete'),
        'roles' => array('admin'),
      ),
      array('deny',  // deny all users
        'users' => array('*'),
      ),
    );
  }

  /**
   * Manages all models.
   */
  public function actionIndex()
  {
    /*
    $logs = AdminLog::model()->findAll();
    foreach ($logs as $log){
      $event = new Timeline();
      $event->category   = 'security';
      $event->event      = 'hack';
      $event->created_at = strtotime($log->dt);
      $event->data       = json_encode(array(
        'message' => $log->log,
        'icon'    => '<i class="fa fa-eye bg-blue"></i>',
        'title'   => 'Попытка нарушения безопасности',
      ));
      $event->save();
    }
*/
    $model = new Timeline('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Timeline']))
      $model->attributes = $_GET['Timeline'];

    $this->render('index', array(
      'model' => $model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id)
  {
    $model = Timeline::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'timeline-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
