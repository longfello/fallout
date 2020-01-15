<?php

class PlayerRaceController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout='//layouts/column2';
	public $pageTitle = 'Управление расами персонажей';

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
	public function actionCreate(){
		$model=new PlayerRace;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(Yii::app()->request->isPostRequest){
			if(isset($_POST['PlayerRace'])) {
				$model->attributes = $_POST['PlayerRace'];
			}
			if($model->save())
				$this->redirect(array('update', 'id' => $model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

   /**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
	public function actionUpdate($id){
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$benefits = [];
		$benefitsList = PlayerRaceBenefitList::model()->findAll();
		foreach ($benefitsList as $one){
			$itemModel = PlayerRaceBenefit::model()->findByAttributes(['race_id' => $model->id, 'benefit_id' => $one->id]);
			if (!$itemModel){
				$itemModel = new PlayerRaceBenefit();
				$itemModel->race_id    = $model->id;
				$itemModel->benefit_id = $one->id;
				$itemModel->value      = 0;
				if (!$itemModel->save()) {
					var_dump($itemModel->errors); die();
				}
			}
			$benefits[$itemModel->id] = $itemModel;
		}

		if(Yii::app()->request->isPostRequest){
			if(isset($_POST['PlayerRace'])) {
				$model->attributes = $_POST['PlayerRace'];
			}
			$benefitValues=Yii::app()->request->getPost('benefit', []);
			foreach ($benefits as &$benefit){
				/** @var $benefit PlayerRaceBenefit */
				$benefit->value = isset($benefitValues[$benefit->id])?$benefitValues[$benefit->id]:0;
			}
			if($model->save()){
				foreach ($benefits as &$benefit){
					/** @var $benefit PlayerRaceBenefit */
					$benefit->save();
				}
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'benefits' => $benefits
		));
	}

	/**
	* Deletes a particular model.
	* If deletion is successful, the browser will be redirected to the 'admin' page.
	* @param integer $id the ID of the model to be deleted
	*/
	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	* Manages all models.
	*/
	public function actionIndex(){
		$model=new PlayerRace('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PlayerRace']))
			$model->attributes=$_GET['PlayerRace'];

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
		$model=PlayerRace::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='player-race-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
