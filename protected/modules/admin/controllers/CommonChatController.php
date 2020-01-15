<?php

class CommonChatController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout='//layouts/column2';
	public $pageTitle = 'Общий чат';
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
	* Manages all models.
	*/
	public function actionIndex(){
		Yii::app()->getModule('chat');
		
		$model=new RChat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RChat']))
			$model->attributes=$_GET['RChat'];

		
		$mid = false;
		$mperiod = false;
		if (isset($_GET['mid'])) {
			$mid = intval($_GET['mid']);
			$CMessage = $this->loadModel($mid);
			
			if ($CMessage) {
				$mperiod = isset($_GET['mperiod'])?floatval($_GET['mperiod']):'0.5';
				$model->unsetAttributes();
				$date = DateTime::createFromFormat('Y-m-d H:i:s', $CMessage->dt);
				$tdate = $date->getTimestamp();
				$model->dt_start_t = $tdate-($mperiod*3600);
				$model->dt_end_t = $tdate+($mperiod*3600);
			} else {
				$mid = false;
			}
		}

		$player_id = false;
		if (isset($_GET['player_id'])) {
			$player_id = intval($_GET['player_id']);

			$model->player_filter = $player_id;
		}

		$this->render('index',array(
			'model'=>$model,
			'mid'=>$mid,
			'mperiod'=>$mperiod,
			'player_id'=>$player_id
		));
	}

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer the ID of the model to be loaded
	*/
	public function loadModel($id){
		$model=RChat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='rchat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
