<?php

class HomeController extends ModuleController
{
  public $pageTitle = 'Управление переводами - главная';
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
        'roles'=>array('admin', 'translate'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionEditable(){
		$id    = Yii::app()->request->getPost('pk', -1);
		$field = Yii::app()->request->getPost('name', '');
		$value = Yii::app()->request->getPost('value', '');

		$model = $this->loadModel($id);
		$model->setAttribute($field, $value);
		$model->save();
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new LanguageTranslate;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LanguageTranslateHome']))
		{
			$model->attributes=$_POST['LanguageTranslateHome'];
      $model->lang_id = Language::DEFAULT_LANGUAGE;
      $model->value = '';
			if($model->save())
				$this->redirect(array('update','id'=>$model->id));
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
	public function actionUpdate($id)
	{
    $model=$this->loadModel($id);

    $languages = Language::getWritableLanguages();
    $all_languages = Language::model()->findAll(array('order' => 'name'));
    $models = array();
    foreach($all_languages as $lang) {
      $models[$lang->slug] = LanguageTranslateHome::model()->findByAttributes(array(
        'lang_id' => $lang->id,
        'slug'    => $model->slug
      ));
      if (!$models[$lang->slug]) {
        $models[$lang->slug] = new LanguageTranslateHome();
        $models[$lang->slug]->lang_id = $lang->id;
        $models[$lang->slug]->slug    = $model->slug;
      }
    }

    if(isset($_POST['LanguageTranslateHome'])) {
      $valid=true;
      foreach($languages as $lang){
        if(isset($_POST['LanguageTranslateHome'][$lang->slug])) {
          $models[$lang->slug]->value=$_POST['LanguageTranslateHome'][$lang->slug]['value'];
          $valid=$models[$lang->slug]->validate() && $valid;
        }
      }
      if($valid){ // все элементы корректны
        foreach($models as $model){
          $model->save();
        }
        $this->redirect(array('index'));
      }
    }

		$this->render('update',array(
      'all_languages' => $all_languages,
      'languages' => $languages,
  		'model'     => $model,
  		'models'    => $models,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new LanguageTranslateHome('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LanguageTranslateHome']))
			$model->attributes=$_GET['LanguageTranslateHome'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LanguageTranslateHome the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=LanguageTranslateHome::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/*
	public function actionFix(){
		header('Content-Type: text/html; charset=utf-8');
		echo "<h1>Добавлены строки которых не было в русском, но были в английском:</h1><br/>";
		$query = "SELECT rlt_en.id, rlt_en.slug FROM rev_language_translate_home as rlt_en
		LEFT JOIN rev_language_translate_home as rlt_ru ON rlt_ru.slug=rlt_en.slug AND rlt_ru.lang_id='1'
		WHERE rlt_en.lang_id='2' AND rlt_ru.id is NULL";

		$deleted_slugs = '';
		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		foreach($res as $one) {
			$slug = $one['slug'];
			if (strlen($slug)>0) {
				if (strpos($slug, "@@@")!==false){
					$slug_arr = mb_split('@@',substr($slug,3));
					$correct = false;
					if(count($slug_arr)==4) {
						$table = $slug_arr[0];
						$field = $slug_arr[1];
						$key = $slug_arr[2];
						$key_val = $slug_arr[3];
						if ($table!='' && $field!='' && $key!='' && $key_val!='') {
							$query = "select `{$field}` as text FROM {$table} WHERE {$key}={$key_val}";
							try {
								$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryRow();
								$query = "INSERT IGNORE INTO rev_language_translate_home SET lang_id = '1', slug = '".$one['slug']."',value='".$res['text']."'";
								$correct = true;
								echo "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>".htmlentities($res['text'],null,'UTF-8')."<br/><hr>";
							} catch(Exception $e) {

							}
						}
					}

					if (!$correct) {
						$query = "DELETE FROM rev_language_translate_home WHERE slug = '".$slug."'";
						$deleted_slugs .= "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>";
					}
				} else {
					$query = "INSERT IGNORE INTO rev_language_translate_home SET lang_id = '1', slug = '".$one['slug']."',value='".$slug."'";
					echo "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>".htmlentities($slug,null,'UTF-8')."<br/><hr>";
				}

				echo $query."<br/>";
			}
		}

		if ($deleted_slugs!='') {
			echo "<h1>Удалены строки с слюгами:</h1>";
			echo $deleted_slugs;
		}

		echo "<h1>Добавлены пустые строки которых не было в английском, но были в русском:</h1><br/>";
		$query = "SELECT rlt_ru.id, rlt_ru.slug, rlt_ru.value FROM rev_language_translate_home as rlt_ru
		LEFT JOIN rev_language_translate_home as rlt_en ON rlt_en.slug=rlt_ru.slug AND rlt_en.lang_id='2'
		WHERE rlt_ru.lang_id='1' AND rlt_en.id is NULL";

		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		foreach($res as $one) {
			$query = "INSERT IGNORE INTO rev_language_translate_home SET lang_id = '2', slug = '".$one['slug']."',value=''";
			echo "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>";

			echo $query."<br/>";
		}
	}
	*/

	/**
	 * Performs the AJAX validation.
	 * @param LanguageTranslateHome $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='language-translate-home-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
