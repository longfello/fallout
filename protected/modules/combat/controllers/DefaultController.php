<?php

class DefaultController extends ModuleController {
    public function init() {
        Yii::app()->theme = 'main';
      $this->pageTitle = t::get('Поединки');
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('combat') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'combat.css'
            )
        );
        Yii::app()->clientScript->registerScript('combat',"
var combatLang = {
  enemyTurn : '".t::encJs('Ход противника')."',
  goGoGo    : '".t::encJs('В бой')."',
};
        ", CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('combat') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR .  'combat.js'
            )
        );
        parent::init();
    }


	public function actionIndex($enemy_id = null){
    if ($enemy_id) {
      $enemy = Data::enemy($enemy_id);

      $this->render('start', array('enemy' => $enemy));
    } else {
      // Инициализация боя
      if (!$combat = Combat::initialization()){
        $this->render('noCombat');
      } else {
        /** @var  $p1 Players */
        $p1 = Players::model()->findByPk($combat->player_1);
        /** @var  $p2 Players */
        $p2 = Players::model()->findByPk($combat->player_2);
        $player = Yii::app()->stat->model;
        $enemy  = ($player->id == $p1->id) ? $p2 : $p1;
          
        $this->render('index', array(
            'player'       => $player,
            'enemy'        => $enemy,
            'combat'       => $combat,
            'p1'           => $p1,
            'p2'           => $p2,
            'isAutomove' => $combat->isAutomove($player)
        ));
      }
    }
	}

    /**
     * Старт боя / ajax
     */
    public function actionGo(){
      if (Yii::app()->request->isPostRequest){
        $player_2 =  Yii::app()->request->getPost('player_2');
        if (!($error = Combat::isAvailableAttack($player_2))){
          Combat::create($player_2);
          $this->redirect('/combat');
        } else {
          Yii::app()->user->setFlash('error', $error);
          $this->redirect(Yii::app()->request->redirect($_SERVER['HTTP_REFERER']));
        }
      } else {
        $this->redirect('/');
      }
    }

    /**
     * Старт боя
     */
    public function actionStart($enemy_id){
      if (!($error = Combat::isAvailableAttack($enemy_id))){
        Combat::create($enemy_id);
        $this->redirect('/combat');
      } else {
        $this->render('error', array('error' => $error));
      }
    }

  public function actionView($id){
    $combat = Combat::model()->findByPk($id);
    if ($combat && $combat->status == Combat::STATUS_INACTIVE) {
      $this->render('combat', array(
        'combat' => $combat
      ));
    } else {
      $this->render('noCombatFounded');
    }
  }
}