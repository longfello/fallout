<?php

class MapController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $pageTitle = 'Загрузка лабиринта';
  public $labyrinthPath = '/images/labyrinth/';
  // Размер полигона в пикселях
  public $polygon_size = 28;

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
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('index', 'delete', 'preview', 'schema', 'image', 'cut', 'upload'),
            'roles' => array('admin'),
        ),
        array('deny',  // deny all users
            'users' => array('*'),
        ),
    );
  }

  public function actions()
  {
    return array(
        'upload'=>array(
          'class'=>'MapUploadAction',
          'path' => basedir.$this->labyrinthPath,
          'publicPath' => $this->labyrinthPath,
          'subfolderVar' => false
        ),
    );
  }
	public function actionDelete()
	{
    $this->remove_files_in_directory(basedir.'/images/labyrinth');

    LabyrinthPoint::model()->updateAll(array('polygon_image' => ''));

    Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Изображения лабиринта удалены.");
		$this->redirect('index');
	}

	public function actionIndex()
	{
    $this->render('index', array(
        'imageLoaded' => glob(basedir."/images/labyrinth/*"),
    ));
	}

  public function actionSchema(){
    if (Yii::app()->request->isPostRequest) {
      if ($_FILES['schema']) {
        if (!empty($_FILES['schema']['name'])) {
          $file_rows = file($_FILES['schema']['tmp_name']);
          Yii::app()->db->commandBuilder->createSqlCommand("TRUNCATE TABLE `rev_labyrinth_point`")->execute();
          $coordinates_data_query = array();
          foreach ($file_rows as $y => $row) {
            $symbols = str_split($row);
            foreach ($symbols as $x => $character) {
              if ($character == '.') {
                $coordinates_data_query[] = "({$y}, {$x}, 1)";
              } else {
                $coordinates_data_query[] = "({$y}, {$x}, 0)";
              }
            }
          }
          $insert_values_row = implode(', ', $coordinates_data_query);
          Yii::app()->db->commandBuilder->createSqlCommand("INSERT INTO `rev_labyrinth_point` (`y`, `x`, `move`) VALUES {$insert_values_row}")->execute();
          Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Схема лабиринта загружена и сконвертирована.");
        }
      }
    }
    $this->redirect('index');
  }

  public function actionImage(){
    if (Yii::app()->request->isPostRequest) {
      if ($_FILES['picture']) {
        if (!empty($_FILES['picture']['name'])) {
          $image_info = getimagesize($_FILES['picture']['tmp_name']);
          if ((!isset($image_info[2])) || (($image_info[0] < 1) && ($image_info[1] < 1))) {
            Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, "Невозможно обратотать файл как изображение. Загрузите изображение в формате PNG.");
            $this->redirect('index');
          } else {
            copy($_FILES['picture']['tmp_name'], basedir.$this->labyrinthPath.'source.png');
            $this->render('image', array(
                'cnt' => LabyrinthPoint::model()->count()
            ));
            return;
          }
        }
      }
    }
    $this->redirect('index');
  }

  public function actionCut(){
    $file = file_get_contents(basedir.$this->labyrinthPath.'source.png');
    $src = imagecreatefromstring($file);
    $dest = imagecreatetruecolor($this->polygon_size, $this->polygon_size);

    $criteria = new CDbCriteria();
    $criteria->addCondition("not polygon_image");
    $criteria->limit  = 500;
    $models = LabyrinthPoint::model()->findAll($criteria);

    foreach($models as $model){
      imagecopy($dest, $src, 0, 0, ($model->x * $this->polygon_size), ($model->y * $this->polygon_size), $this->polygon_size, $this->polygon_size);
      $hash_image_name = $this->get_random_filename(basedir.'/images/labyrinth', 'png') . '.png';
      imagepng($dest, basedir.'/images/labyrinth/' . $hash_image_name);
      $model->polygon_image = $hash_image_name;
      $model->save();
//      _mysql_exec("UPDATE `rev_labyrinth_point` SET `polygon_image` = '$hash_image_name' WHERE `y` = {$y} AND `x` = {$x}");
    }

    $criteria->limit = false;
    $left = LabyrinthPoint::model()->count($criteria);
    if ($left == 0) {
      Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Картинка загружена и нарезана.");
    }
    echo(json_encode(array(
      'left' => $left
    )));
  }

  // Получить случайное имя для файла
  private function get_random_filename($path, $extension='')
  {
    $extension = $extension ? '.' . $extension : '';
    $path = $path ? $path . '/' : '';

    do {
      $name = substr(md5(microtime() . rand(0, 9999)), 0, 10);
      $file = $path . $name . $extension;
    } while (file_exists($file));

    return $name;
  }


  private function remove_files_in_directory($path)
  {
    $files = glob($path . "/*");
    array_map(create_function('$a', 'unlink($a);'), $files);
  }


  // Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}