<?php

class InviteLogController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout='//layouts/column2';
    public $pageTitle = 'Приглашения';

	/**
	* @return array action filters
	*/
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	* Specifies the access control rules.
	* This method is used by the 'accessControl' filter.
	* @return array access control rules
	*/
    public function accessRules(){
        return array(
            array('allow',
                'actions' => array('index'),
                'roles' => array('admin', 'Модератор', 'Чат-модератор'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }


	/**
	* Lists all models.
	*/
	public function actionIndex(){
        $model=new InviteLog('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['InviteLog']))
            $model->attributes=$_GET['InviteLog'];

        $this->render('index',array(
            'model'=>$model,
        ));
	}


	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer the ID of the model to be loaded
	*/
	public function loadModel($id){
		$model=InviteLog::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='invite-log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
