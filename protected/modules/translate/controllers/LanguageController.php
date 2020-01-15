<?php

class LanguageController extends ModuleController
{
  public $pageTitle = 'Управление языками';
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
            'roles'=>array('admin'),
        ),
        array('deny',  // deny all users
            'users'=>array('*'),
        ),
		);
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
		$model=new Language;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Language']))
		{
			$model->attributes=$_POST['Language'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Language']))
		{
			$model->attributes=$_POST['Language'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Language');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Language('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Language']))
			$model->attributes=$_GET['Language'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionExport($id, $type = t::MODEL_GAME)
	{
		$lang_id = intval($id);
		$model=$this->loadModel($lang_id);
		$table = ($type == t::MODEL_GAME)?"rev_language_translate":"rev_language_translate_home";

/*
		$rows = Yii::app()->db->createCommand("
SELECT DISTINCT rlt0.slug, rlt0.value as val_default, rlt1.value as val_lang FROM {$table} rlt0
LEFT JOIN {$table} rlt1 ON rlt1.slug = rlt0.slug AND rlt1.lang_id={$model->id}
WHERE rlt0.value > ''
GROUP BY rlt0.slug
ORDER BY rlt0.slug, rlt0.lang_id=".Language::DEFAULT_LANGUAGE."
")->queryAll();
		*/

		$rows = Yii::app()->db->createCommand("SELECT rlt1.slug, rlt2.value as val_default, rlt1.value as val_lang FROM {$table} as rlt1
			INNER JOIN {$table} rlt2 ON rlt2.slug = rlt1.slug AND rlt2.lang_id=".Language::DEFAULT_LANGUAGE."
			WHERE rlt1.lang_id=".$model->id)->queryAll();

		//$f_csv = new File_FGetCSV();
		$fp = fopen('php://temp', 'w');
		$cur = 0;
		fputs($fp, '<?xml version="1.0" encoding="UTF-8"?>');
		fputs($fp, '<document>');
		foreach($rows as $row) {
			$cur++;
			fputs($fp, "\n<str><slug>".htmlspecialchars($row['slug'])."</slug><ru>".htmlspecialchars($row['val_default'])."</ru><lang>".htmlspecialchars($row['val_lang'])."</lang></str>");
		}
		fputs($fp, '\n</document>');
		rewind($fp);
		Yii::app()->request->sendFile("export_".$model->slug.".xml",stream_get_contents($fp));
		fclose($fp);
	}

	public function actionImport($id, $type = t::MODEL_GAME) {
		$lang=$this->loadModel($id);

		$model = new File;
		$form = new CForm('application.modules.translate.views.language._form_import', $model);

		$files = CUploadedFile::getInstancesByName('xml');
		$table = ($type == t::MODEL_GAME)?LanguageTranslate::model():LanguageTranslateHome::model();

		if(count($files) > 0)
		{
			$file = $files[0];
			$name = $file->getTempName();
			$col_updated = 0;
			$col_new = 0;
			$col_rows = 0;

			$xml_file =simplexml_load_file($name);
			foreach ($xml_file->str as $str) {
				if (strlen($str->slug)>0) {
					$col_rows++;
					$model = $table->findByAttributes(array('slug' => $str->slug, 'lang_id' => $id));
					if ($model) {
						$model->value = htmlspecialchars_decode($str->lang);
						$model->save();
						$col_updated++;
					} else {
						$col_new++;
					}
				}
			}
			Yii::app()->user->setFlash('message', 'Файл успешно импортирован. Всего <b>'.$col_rows.'</b> строк. Обновлено <b>'.$col_updated.'</b>, пропущено <b>'.$col_new.'</b>');
			$this->redirect(array('language/admin'));
		}

		$this->render('import',array(
			'lang'=>$lang,
			'form'=>$form
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Language the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Language::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Language $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='language-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}