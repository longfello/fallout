<?php

class StatisticController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $pageTitle = 'Статистика';

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
            'actions' => array('index', 'leveling', 'ref', 'adv','load','loadPlayer','loadAdv','loadPlayerAdv'),
            'roles' => array('admin'),
        ),
        array('deny',  // deny all users
            'users' => array('*'),
        ),
    );
  }

  public function beforeAction($action){
    Yii::app()->clientScript->registerScriptFile(Yii::app()->getModule('admin')->getAssetsUrl() . '/js/plugins/chartjs/Chart.min.js', CClientScript::POS_END);
    return parent::beforeAction($action);
  }

	public function actionIndex()
	{
    $this->pageTitle .= ' - главная';
		$this->render('index');
	}

	public function actionLeveling()
	{
    $this->pageTitle .= ' - по уровням';
    $query = "
SELECT `p`.`level`,  COUNT(`p`.`level`) AS `count_players`
FROM `players` AS `p`
GROUP BY `p`.`level`
ORDER BY `p`.`level`
";
    $result   = Yii::app()->db->commandBuilder->createSqlCommand($query)->queryAll();
    $level  = array();
    $cnt   = array();
    foreach($result as $one) {
      $level[] = $one['level'];
      $cnt[]  = $one['count_players'];
    }
		$this->render('leveling', array(
      'level' => $level,
      'cnt'   => $cnt,
    ));
	}

	public function actionRef()
	{
    $this->pageTitle .= ' - по рефералам';

    $sql='SELECT id, `user`, ref FROM `players` WHERE id IN(SELECT DISTINCT(ref) FROM `players`) ';
    $players=Yii::app()->db->commandBuilder->createSqlCommand($sql)->queryAll();

    $dataProvider = new CArrayDataProvider($players, array(
      'keyField' => 'id',
      'pagination' => array(
        'pageSize' => 50,
      ),
    ));

    $this->render('ref', array(
      'dataProvider' => $dataProvider
    ));
	}

  public function actionAdv()
  {
    $this->pageTitle .= ' - по рекламе';

    $dataProvider = new CActiveDataProvider('Players', array(
      'pagination' => array(
        'pageSize' => 25,
      ),
    ));

    $this->render('adv', array(
      'dataProvider' => $dataProvider
    ));
  }

  public function actionLoad()
  {
    $uid = Yii::app()->request->getPost('uid', -1);
    $sql='SELECT level, COUNT(level) AS count_players FROM `players` WHERE ref='.$uid.' GROUP BY level';
    $players=Yii::app()->db->commandBuilder->createSqlCommand($sql)->queryAll();
    foreach($players as $value) {
      echo '<div class="show-player"><i class="fa fa-plus-circle" data-uid="'.$uid.'"  data-level="'.$value['level'].'"></i><i class="fa fa-minus-circle hidden"></i>';
      echo 'Игроков уровня ' . $value['level'] . ': ';
      echo $value['count_players'];
      echo '<div class="players well" >';
      echo '</div></div>';
    }
    Yii::app()->end();
  }

  public function actionLoadPlayer()
  {
    $uid = Yii::app()->request->getPost('uid', -1);
    $level = Yii::app()->request->getPost('level', -1);
    $sql='SELECT id, user FROM `players` WHERE ref='.$uid.' AND level='.$level;
    $players=Players::model()->findAllBySql($sql);
    echo '<ul>';
    foreach($players as $value) {
      ?>
      <li>
        <a href="<?echo Yii::app()->createUrl('admin/players/update?id='.$value['id'])?>">
          <?php echo $value['id'].'. '.$value['user']; ?>
        </a>
      </li>
    <?php
    }
    echo '<ul>';
    Yii::app()->end();
  }

  public function actionLoadAdv()
  {
    $uid = Yii::app()->request->getPost('uid', -1);
    //$uid=1;
    $sql='SELECT level, COUNT(level) AS count_players FROM `players` LEFT JOIN reg_source ON players.id = reg_source.player WHERE source='.$uid.' GROUP BY level';
    $players=Yii::app()->db->commandBuilder->createSqlCommand($sql)->queryAll();
    foreach($players as $value) {
      echo '<div class="show-player"><i class="fa fa-plus-circle" data-uid="'.$uid.'"  data-level="'.$value['level'].'"></i><i class="fa fa-minus-circle hidden"></i>';
      echo 'Игроков уровня '.$value['level'].': ';
      echo $value['count_players'];
      echo '<div class="players well" >';

      echo '</div></div>';
    }
    Yii::app()->end();
  }

  public function actionLoadPlayerAdv()
  {
    $uid = Yii::app()->request->getPost('uid', -1);
    $level = Yii::app()->request->getPost('level', -1);
    $sql='SELECT players.id, user FROM `players` LEFT JOIN reg_source ON players.id = reg_source.player WHERE source='.$uid.' AND level='.$level;
    $players=Players::model()->findAllBySql($sql);
    echo '<ul>';
    foreach($players as $value) {
      ?>
      <li>
        <a href="<?echo Yii::app()->createUrl('admin/players/update?id='.$value['id'])?>">
          <?php echo $value['id'].'. '.$value['user']; ?>
        </a>
      </li>
    <?php
    }
    echo '<ul>';
    Yii::app()->end();
  }

}