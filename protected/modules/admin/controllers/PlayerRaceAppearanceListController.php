<?php

class PlayerRaceAppearanceListController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $pageTitle = 'Управление внешним видом рас персонажей';

	/**
	 * @return array action filters
	 */
	public function filters(){
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
	public function actionCreate($race_id){
		$model=new PlayerRaceAppearanceList;
		$model->race_id = $race_id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PlayerRaceAppearanceList'])){
			$model->attributes=$_POST['PlayerRaceAppearanceList'];
			if($model->save()){
				$layouts = explode(',',$model->layout->layouts);
				foreach ($layouts as $layout){
					$image = CUploadedFile::getInstanceByName('PlayerRaceAppearanceList[image]['.$layout.']');
					if ($image) {
						$image->saveAs(basedir.$model->getImagePath($layout));
						$model->original_filename = $image->name;
						$model->save();
					}
				}
				$this->redirect(array('update','id'=>$model->id,));
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

		if(isset($_POST['PlayerRaceAppearanceList'])){
			$model->attributes=$_POST['PlayerRaceAppearanceList'];
			if($model->save()){
				$layouts = explode(',',$model->layout->layouts);
				foreach ($layouts as $layout){
					$image = CUploadedFile::getInstanceByName('PlayerRaceAppearanceList[image]['.$layout.']');
					if ($image) {
						$image->saveAs(basedir.$model->getImagePath($layout));
						$model->original_filename = $image->name;
						$model->save();
					}
				}
				$this->redirect(array('index','race_id'=>$model->race_id));
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
		if(Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			$race_id = $model->race_id;
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index','race_id'=>$race_id));
		} else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	* Manages all models.
	*/
	public function actionIndex($race_id){
		$model=new PlayerRaceAppearanceList('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PlayerRaceAppearanceList']))
			$model->attributes=$_GET['PlayerRaceAppearanceList'];
		$model->race_id = $race_id;

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
		$model=PlayerRaceAppearanceList::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='player-race-appearance-list-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
