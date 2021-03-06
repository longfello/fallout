<?php

class ClanStoreHistoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $pageTitle = 'Журнал кладовки';

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
			array('allow',
				'actions' => array('index', 'delete'),
				'roles' => array('admin', 'Модератор', 'Чат-модератор'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
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
			if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		} else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	* Manages all models.
	*/
	public function actionIndex($clan_id){
		$model=new CstoreHistory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CstoreHistory']))
			$model->attributes=$_GET['CstoreHistory'];
		$model->clan = $clan_id;
		
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
		$model=CstoreHistory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='cstore-history-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
