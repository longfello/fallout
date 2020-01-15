<?php

class PresentsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $pageTitle = 'Управление подарками';

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
		$model=new Presents;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Presents'])){
			$model->attributes=$_POST['Presents'];
			$model->generatePic();
			if($model->save()){
				$model->image = CUploadedFile::getInstance($model,'image');
				if ($model->image) {
					$model->image->saveAs(basedir . $model->getPath(Presents::IMAGE_SMALL));
				}
				$model->imageBig = CUploadedFile::getInstance($model,'imageBig');
				if ($model->imageBig) {
					$model->imageBig->saveAs(basedir . $model->getPath(Presents::IMAGE_BIG));
				}
				$this->redirect(array('index'));
			}
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

		if(isset($_POST['Presents'])){
			$model->attributes=$_POST['Presents'];
			$model->generatePic();
			if($model->save()){
				$model->image = CUploadedFile::getInstance($model,'image');
				if ($model->image) {
					$model->image->saveAs(basedir . $model->getPath(Presents::IMAGE_SMALL));
				}
				$model->imageBig = CUploadedFile::getInstance($model,'imageBig');
				if ($model->imageBig) {
					$model->imageBig->saveAs(basedir . $model->getPath(Presents::IMAGE_BIG));
				}

				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

   /**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
	public function actionView($id){
		$model=$this->loadModel($id);

		$presents=new PlayerPresents('search');
		$presents->unsetAttributes();  // clear any default values
		if(isset($_GET['PlayerPresents']))
			$model->attributes=$_GET['PlayerPresents'];

		$this->render('view',array(
			'model'=>$model,
			'presents'=>$presents,
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
		$model=new Presents('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Presents']))
			$model->attributes=$_GET['Presents'];

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
		$model=Presents::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='presents-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
