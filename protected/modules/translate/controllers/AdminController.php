<?php

class AdminController extends ModuleController
{
  public $pageTitle = 'Управление переводами - игра';
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

	public function actionEditable(){
		$id    = Yii::app()->request->getPost('pk', -1);
		$field = Yii::app()->request->getPost('name', '');
		$value = Yii::app()->request->getPost('value', '');

		$model = $this->loadModel($id);
		$model->setAttribute($field, $value);
		$model->save();
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

		if(isset($_POST['LanguageTranslate']))
		{
			$model->attributes=$_POST['LanguageTranslate'];
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
      $models[$lang->slug] = LanguageTranslate::model()->findByAttributes(array(
        'lang_id' => $lang->id,
        'slug'    => $model->slug
      ));
      if (!$models[$lang->slug]) {
        $models[$lang->slug] = new LanguageTranslate();
        $models[$lang->slug]->lang_id = $lang->id;
        $models[$lang->slug]->slug    = $model->slug;
      }
    }

    if(isset($_POST['LanguageTranslate'])) {
      $valid=true;
      foreach($languages as $lang){
        if(isset($_POST['LanguageTranslate'][$lang->slug])) {
          $models[$lang->slug]->value=$_POST['LanguageTranslate'][$lang->slug]['value'];
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
		$model=new LanguageTranslate('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LanguageTranslate']))
			$model->attributes=$_GET['LanguageTranslate'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

  public function actionTest(){
    t::getInstance()->setLanguage('ru');
    echo(t::get('test'));
    die();
  }

	public function actionFix(){
		header('Content-Type: text/html; charset=utf-8');
		$errors = '';
		echo "<h1>Добавлены строки которых не было в русском, но были в английском:</h1>";
		$query = "SELECT rlt_en.id, rlt_en.slug FROM rev_language_translate as rlt_en
		LEFT JOIN rev_language_translate as rlt_ru ON rlt_ru.slug=rlt_en.slug AND rlt_ru.lang_id='1'
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
								$model = new LanguageTranslate();
								$model->slug    = $one['slug'];
								$model->value   = $res['text'];
								$model->lang_id = 1;
								try {
									$model->save();
									echo "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>".htmlentities($res['text'],null,'UTF-8')."<br/><hr>";
								} catch (Exception $e) {
									$errors .= $e->getMessage()."<br/>";
								}
								$correct = true;
							} catch(Exception $e) {

							}
						}
					}

					if (!$correct) {
						$command = Yii::app()->db->createCommand();
						$command->delete('rev_language_translate', 'slug=:slug', array(':slug'=>$slug));
						$deleted_slugs .= "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>";
					}
				} else {
					$model = new LanguageTranslate();
					$model->slug    = $one['slug'];
					$model->value   = $slug;
					$model->lang_id = 1;
					try {
						$model->save();
						echo "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>".htmlentities($slug,null,'UTF-8')."<br/><hr>";
					} catch (Exception $e) {
						$errors .= $e->getMessage()."<br/>";
					}
				}
			}
		}

		if ($deleted_slugs!='') {
			echo "<h1>Удалены строки с слюгами:</h1>";
			echo $deleted_slugs;
		}

		echo "<h1>Добавлены fail_text из таблицы crafting в переводы:</h1>";
		$query = "SELECT id, fail_text FROM crafting";

		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		foreach($res as $one) {
			$slug = "@@@crafting@@fail_text@@id@@".$one['id'];
			$model = new LanguageTranslate();
			$model->slug    = $slug;
			$model->value   = $one['fail_text'];
			$model->lang_id = 1;
			try {
				$model->save();
				echo "<b>".htmlentities($slug,null,'UTF-8')."</b><br/>".htmlentities($one['fail_text'],null,'UTF-8')."<br/><hr>";
			} catch (Exception $e) {
				$errors .= $e->getMessage()."<br/>";
			}
		}

		echo "<h1>Добавлены пустые строки которых не было в английском, но были в русском:</h1><br/>";
		$query = "SELECT rlt_ru.id, rlt_ru.slug, rlt_ru.value FROM rev_language_translate as rlt_ru
		LEFT JOIN rev_language_translate as rlt_en ON rlt_en.slug=rlt_ru.slug AND rlt_en.lang_id='2'
		WHERE rlt_ru.lang_id='1' AND rlt_en.id is NULL";

		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		foreach($res as $one) {
			$model = new LanguageTranslate();
			$model->slug    = $one['slug'];
			$model->value   = '';
			$model->lang_id = 2;
			try {
				$model->save();
				echo "<b>".htmlentities($one['slug'],null,'UTF-8')."</b><br/>";
			} catch (Exception $e) {
				$errors .= $e->getMessage()."<br/>";
			}
		}

		if ($errors!='') {
			echo "<h1>Ошибки:</h1>";
			echo $errors;
		}
		if (isset(Yii::app()->cache)) Yii::app()->cache->flush();
	}

	public function actionNews() {
		header('Content-Type: text/html; charset=utf-8');
		$query = "SELECT id, title FROM news";


		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		$count = 0;
		$res_str = '';
		foreach($res as $one) {
			$slug = "@@@news@@title@@id@@".$one['id'];
			$query = "select `value` as text FROM rev_language_translate WHERE slug='{$slug}' AND lang_id='1'";
			$translate = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryRow();
			if ($translate && $translate['text'] && $translate['text']!=$one['title']) {
				$res_str .= $one['id']." ==> ".$one['title']." <> ".$translate['text']."<br/>";
				$count++;
				$command = Yii::app()->db->createCommand();
				$command->delete('rev_language_translate', 'slug=:slug', array(':slug'=>$slug));
			}
		}
		echo "Всего :".$count."<hr/>";
		echo $res_str;
		if (isset(Yii::app()->cache)) Yii::app()->cache->flush();
	}

	public function actionCombat() {
		header('Content-Type: text/html; charset=utf-8');

		$query = "SELECT id, phrase FROM rev_combat_phrases";

		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		$res_str = '';
		foreach($res as $one) {
			$slug = "@@@rev_combat_phrases@@phrase@@id@@".$one['id'];
			$res_str .= "<b>".$slug."</b><br/>";
			$res_str .= $one['phrase']."<br/><hr/>";
			/*
			$query = "select `value` as text FROM rev_language_translate WHERE slug='{$slug}' AND lang_id='2'";
			$translate = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryRow();

			if ((!$translate) || ($translate['text']=='') || ($translate['text']===$one['phrase'])) {
				$res_str .= $slug." ==> ".$one['phrase']." --- ".$translate['text']."<br/>";
			}
			*/
		}
		echo $res_str;
	}

	public function actionCrafting() {
		header('Content-Type: text/html; charset=utf-8');

		$query = "SELECT id, fail_text FROM crafting";

		$res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
		$res_str = '';
		foreach($res as $one) {
			$slug = "@@@crafting@@fail_text@@id@@".$one['id'];

			$query = "select `value` as text FROM rev_language_translate WHERE slug='{$slug}' AND lang_id='2'";
			$translate = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryRow();

			if ((!$translate) || ($translate['text']===$one['fail_text'] && $one['fail_text']!='')) {
				$res_str .= "<b>".$slug."</b><br/>";
				$res_str .= $one['fail_text']."<br/>";
				$res_str .= $translate['text']."<br/><hr/>";
			}

		}
		echo $res_str;
	}

	public function actionStrings() {
		echo t::get('В кладовку вашего клана выданы ресурсы: %s х %s шт. Администрация');
		echo t::get('В казну вашего клана выдано золото: %s<img src="images/gold.png">. Администрация');
		echo t::get('В казну вашего клана выданы крышки: %s<img src="images/platinum.png">. Администрация');
		echo t::get('Из казны вашего клана изъято золото: %s<img src="images/gold.png">. Администрация');
		echo t::get('Из казны вашего клана изъяты крышки: %s<img src="images/platinum.png">. Администрация');
		echo t::get('<b>Администрация</b> выдала в кладовую клана');
		echo t::get('<b>Администрация</b> выдала в казну клана');
		echo t::get('<b>Администрация</b> забрала из казны клана');
		echo t::get('золото|золота|золота');
		echo t::get('крышечку|крышечки|крышечек');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LanguageTranslate the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=LanguageTranslate::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LanguageTranslate $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='language-translate-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
