<?php

class NkrPricesController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
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
                'actions' => array('load','AddPrice','index', 'create', 'update', 'delete'),
                'roles' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionLoad($id) {
        $model = new NkrPrices( 'search' );
        $model->unsetAttributes();  // clear any default values
        $model->event_id = $id;

        $this->renderPartial( 'load', array(
            'model' => $model,
            'id' => $id,
        ) );
    }

    public function actionAddPrice($id) {
        $model = new NkrPrices;
        $model->event_id = $id;

        if ( isset( $_POST['NkrPrices'] ) ) {
            $model->attributes = $_POST['NkrPrices'];
            if ( $model->save() ) {
                if (Yii::app()->request->isAjaxRequest){
                    Popup::close();
                } else {
                    $this->redirect( array( 'update', 'id' => $model->id ) );
                }
            }
        }


        $this->renderPopup( 'create', array(
            'model' => $model,
            'id'    => $id,
        ), 'Добавить цену', Popup::btnSave('Добавить'));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new NkrPrices;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['NkrPrices'])) {
            $model->attributes = $_POST['NkrPrices'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id));
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

        if (isset($_POST['NkrPrices'])) {
            $model->attributes = $_POST['NkrPrices'];
            if ($model->save())
                if (Yii::app()->request->isAjaxRequest){
                    Popup::close();
                } else {
                    $this->redirect( array( 'index') );
                }
        }

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPopup( 'update', array(
                'model' => $model,
            ), 'Редактировать цены', Popup::btnSave());
        } else {
            $this->render( 'update', array(
                'model' => $model,
            ) );
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel( $id )->delete();
        Popup::close();
        Yii::app()->end();
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('NkrPrices');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = NkrPrices::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'nkr-prices-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
