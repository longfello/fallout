<?php

class ErrorsController extends Controller
{
  public $layout = '//layouts/column2';
  public $pageTitle = 'Журнал ошибок';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete + erase', // we only allow deletion via POST request
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
        'actions' => array('index', 'delete', 'view', 'truncate', 'erase'),
        'roles' => array('admin'),
      ),
      array('deny',  // deny all users
        'users' => array('*'),
      ),
    );
  }

  public function actionView($id)
  {
    $this->render('view', array(
      'model' => $this->loadModel($id),
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
      $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
        $this->redirect(array('index'));
    } else
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionErase()
  {
    if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
      if ($q= Yii::app()->request->getPost('q', '')) {
	      $criteria = new CDbCriteria();
	      $criteria->addCondition( "message like :q" );
	      $criteria->params[':q'] = '%' . $q . '%';
	      Errors::model()->deleteAll( $criteria );
      }
      $this->redirect( array( 'index' ) );
    } else
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
  }

  public function actionTruncate()
  {
    Yii::app()->db->createCommand()->truncateTable(Errors::model()->tableName());
    $this->redirect(array('index'));
  }

  /**
   * Manages all models.
   */
  public function actionIndex()
  {
    $model = new Errors('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Errors']))
      $model->attributes = $_GET['Errors'];

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
    $model = Errors::model()->findByPk($id);
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
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'errors-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
