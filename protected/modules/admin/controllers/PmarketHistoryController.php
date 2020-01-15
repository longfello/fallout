<?php

class PmarketHistoryController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout='//layouts/column2';
	public $pageTitle = 'Продажа крышек за золото';
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'roles' => array('admin'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex(){
		$model=new PmarketHistory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PmarketHistory']))
			$model->attributes=$_GET['PmarketHistory'];

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
		$model=PmarketHistory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='pmarket-history-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
