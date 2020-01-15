<?php

class PwCostsController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $pageTitle = 'Управление ценами крышек';

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
                'actions' => array('index', 'delete', 'create', 'update'),
                'roles' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new PwCosts;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['PwCosts'])) {
            $model->attributes = $_POST['PwCosts'];
            $model->image_tmp = CUploadedFile::getInstance($model,'image_tmp');
            if ($model->image_tmp) {
                $model->image = $model->image_tmp->name;
            }
            if ($model->save()) {
                if ($model->image_tmp) {
                    $model->image_tmp->saveAs(basedir . '/images/costs/' . $model->image);
                }
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['PwCosts'])) {
            $model->attributes = $_POST['PwCosts'];
            $model->image_tmp = CUploadedFile::getInstance($model,'image_tmp');
            if ($model->image_tmp) {
                $model->image = $model->image_tmp->name;
            }
            if ($model->save()) {
                if ($model->image_tmp) {
                    $model->image_tmp->saveAs(basedir.'/images/costs/'.$model->image);
                }
                $this->redirect(array('index'));
            }
        }

	    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->getModule('admin')->getOwnAssetsUrl().'/js/pw-costs-box.js');
	    Yii::app()->getClientScript()->registerCssFile(Yii::app()->getModule('admin')->getOwnAssetsUrl().'/css/pw-costs-box.css');
        $this->render('update', array(
            'model' => $model,
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new PwCosts('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PwCosts']))
            $model->attributes = $_GET['PwCosts'];

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
        $model = PwCosts::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pw-costs-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
