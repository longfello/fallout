<?php

class CombatController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  const PLAYER1 = 'test_kiborg_1';
  const PLAYER2 = 'test_kiborg_2';

  public $layout = '//layouts/column2';
  public $pageTitle = 'Тест боевой системы';

  public function init(){
    Yii::app()->getModule('combat');
  }

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
            'actions' => array('index', 'go'),
            'roles' => array('admin'),
        ),
        array('deny',  // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * Manages all models.
   */
  public function actionIndex()
  {
	  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->getModule('admin')->getOwnAssetsUrl().'/js/combat.js');
	  Yii::app()->clientScript->registerCssFile(
		  Yii::app()->assetManager->publish(
			  Yii::getPathOfAlias('combat') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'combat.css'
		  )
	  );


	  $this->savePlayer();

  	$player1 = $this->initPlayer(self::PLAYER1);
  	$player2 = $this->initPlayer(self::PLAYER2);

	  $this->render('index', array(
		  'player1' => $player1,
		  'player2' => $player2,
	  ));
  }

  public function actionGo(){
	  $player1 = $this->initPlayer(self::PLAYER1);
	  $player2 = $this->initPlayer(self::PLAYER2);

	  $uid = Yii::app()->stat->id;
	  Yii::app()->stat->_loadModel($player1->id);
	  Combat::create($player2->id);

	  $combat = Combat::initialization();
	  /*
	  $round = $combat->getRound(Yii::app()->stat->id);
	  $round->setParam(null, null);
*/
	  $log = array();
	  while ($combat->status == Combat::STATUS_ACTIVE) {
		  $combat->isRoundDone();
		  $combat->nextRound();
		  $log[] = $combat->getLog();
	  }

	  Yii::app()->stat->_loadModel($uid);

	  $this->renderPartial('combat', array(
		  'combat' => $combat
	  ));
  }

  public function initPlayer($name){

  	$model = Players::model()->findByAttributes(array('user' => $name));
	if (!$model) {
		$model = new Players();
		$model->email_confirmed = Players::NO;
		$model->user     = $name;
		$model->email    = 'robot@gmail.com';
		$model->pass2    = '';
		$model->profile  =  '';
		$model->gender   =  Players::GENDER_MALE;
		$model->ref      = null;
		$model->ip       = '127.0.0.1';
		$model->reg_date = time();
		$model->save(false);

		_mysql_exec("INSERT INTO `appearance` SET `user_id` = {$model->id}, `avatar` = '1.png', `hairstyle` = '1.png', `free_set_used` = 0") ;
	}
    return $model;
  }


  public function savePlayer(){
	  if (Yii::app()->request->isPostRequest && $info = Yii::app()->request->getPost('Players', false)) {
		  $model = Players::model()->findByPk($info['id']);
		  if ($model) {
			  $model->attributes = $_POST['Players'];
			  $model->save(false);

			  $this->redirect('/admin/combat');
		  }
	  }
  }
}
