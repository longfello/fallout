<?php

class NpcController extends Controller
{
	/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout = '//layouts/column2';
	public $pageTitle = 'Управление мобами';

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
			array('allow',
				'actions' => array('index', 'delete', 'create', 'update', 'drop'),
				'roles' => array('admin'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionDrop(){
		$model=new NpcDropForm();
		$removeModel=new NpcDropRemoveForm();
		if(isset($_POST['NpcDropForm'])){
			// collects user input data
			$model->attributes=$_POST['NpcDropForm'];
			// validates user input and redirect to previous page if validated
			if($model->validate()) {
				$mobs = Npc::model()->findAllByAttributes(['id' => explode(',',$model->npc_ids)]);
				foreach ($mobs as $npcModel){
					$drop = new Npcdrop();
					$drop->npc     = $npcModel->id;
					$drop->item    = $model->equipment_id;
					$drop->amax    = $model->qFrom;
					$drop->amin    = $model->qTo;
					$drop->chance  = mt_rand($model->numFrom, $model->numTo);
					$drop->toprand = mt_rand($model->deFrom,  $model->deTo);
					$drop->notify  = 0;
					if (!$drop->save()) {
						var_dump($drop->errors);
						die();
					}

				}
				Yii::app()->user->setFlash('success', "Дроп добавлен!");
				$model=new NpcDropForm();
			}
		}
		if(isset($_POST['NpcDropRemoveForm'])){
			// collects user input data
			$removeModel->attributes=$_POST['NpcDropRemoveForm'];
			// validates user input and redirect to previous page if validated
			if($removeModel->validate()) {

				$equipment = Equipment::model()->findByPk($removeModel->equipment_id);

				$drops = Npcdrop::model()->findAllByAttributes(['item' => $removeModel->equipment_id]);
				$mobs = [];
				foreach ($drops as $one) {
					$mobs[] = $one->mob->name;
				}
				$message = 'Предмет "'. $equipment->name .'" удален у '. count($mobs) .' мобов: '. implode(', ', $mobs);
				Npcdrop::model()->deleteAllByAttributes(['item' => $removeModel->equipment_id]);
				Yii::app()->user->setFlash('success', $message);
				$model=new NpcDropForm();
			}
		}
		$this->render('drop', [
			'model' => $model,
			'removeModel' => $removeModel,
		]);
	}

	/**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate(){
		$model=new Npc;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Npc'])){
			$model->attributes=$_POST['Npc'];


			if($model->save())
				$model->image_tmp = CUploadedFile::getInstance($model,'image_tmp');
				if ($model->image_tmp) {
					$model->pic = 10000*$model->id;
					$model->image_tmp->saveAs(basedir . '/avatars/npc_' . $model->pic . '.png');
					$model->save();
				}
				$this->saveDrop($model);
				$this->redirect(array('index'));
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

		if(isset($_POST['Npc'])){
			$model->attributes=$_POST['Npc'];

			if($model->save())
				$model->image_tmp = CUploadedFile::getInstance($model,'image_tmp');
				if ($model->image_tmp) {
					$model->pic = 10000*$model->id;
					$model->image_tmp->saveAs(basedir . '/avatars/npc_' . $model->pic . '.png');
					$model->save();
				}
				$this->saveDrop($model);
				$this->redirect(array('index'));
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
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	* Manages all models.
	*/
	public function actionIndex(){
		$model=new Npc('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Npc']))
			$model->attributes=$_GET['Npc'];

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
		$model=Npc::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='npc-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function saveDrop($npcModel){
		$drop = Yii::app()->request->getPost('Npcdrop');
		if ($drop) {
			Npcdrop::model()->deleteAllByAttributes(['npc' => $npcModel->id]);
			foreach ($drop['item'] as $key => $value){
				if ($drop['item'][$key]) {
					$model = new Npcdrop();
					$model->npc     = $npcModel->id;
					$model->item    = $drop['item'][$key];
					$model->amax    = $drop['amax'][$key];
					$model->amin    = $drop['amin'][$key];
					$model->chance  = $drop['chance'][$key];
					$model->toprand = $drop['toprand'][$key];
					$model->notify  = $drop['notify'][$key];
					if (!$model->save()) {
						var_dump($model->errors);
						die();
					}
				}
			}
		}
	}
}
