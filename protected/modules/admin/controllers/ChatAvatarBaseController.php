<?php

class ChatAvatarBaseController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout='//layouts/column2';
    public $pageTitle = 'Управление аватарками в чате';
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'index', 'delete'),
                'roles'   => array( 'admin' ),
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
        Yii::app()->getModule('chat');
		$model=new ChatAvatarBase;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ChatAvatarBase'])){
			$model->attributes=$_POST['ChatAvatarBase'];
            if($model->save()) {
                $model->image_tmp = CUploadedFile::getInstance($model,'image_tmp');
                if ($model->image_tmp) {
                    $model->image = $model->image_tmp->name;
                    $model->image_tmp->saveAs(basedir . '/images/chat/avatars/' . $model->image);
                    $model->save();
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
        Yii::app()->getModule('chat');
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ChatAvatarBase'])){
			$model->attributes=$_POST['ChatAvatarBase'];

            if($model->save()) {
                $model->image_tmp = CUploadedFile::getInstance($model,'image_tmp');
                if ($model->image_tmp) {
                    $model->image = $model->image_tmp->name;
                    $model->image_tmp->saveAs(basedir . '/images/chat/avatars/' . $model->image);
                    $model->save();
                }
                $this->redirect(array('index'));
            }
        }

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	* Deletes a particular model.
	* If deletion is successful, the browser will be redirected to the 'admin' page.
	* @param integer $id the ID of the model to be deleted
	*/
	public function actionDelete($id){
        Yii::app()->getModule('chat');
		if(Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	* Lists all models.
	*/
	public function actionIndex(){
        Yii::app()->getModule('chat');
        $model=new ChatAvatarBase('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['ChatAvatarBase']))
            $model->attributes=$_GET['ChatAvatarBase'];

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
        Yii::app()->getModule('chat');
		$model=ChatAvatarBase::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='chat-avatar-base-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
