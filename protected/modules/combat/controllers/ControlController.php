<?php

class ControlController extends ModuleController {
    public function beforeAction($action) {
      if (!Yii::app()->request->isAjaxRequest) return false;
      return parent::beforeAction($action);
    }


	public function actionManual(){
    if (!$combat = Combat::initialization()){
      Yii::app()->end(404);
    } else {
      $combat->setAutomove(false);
      Yii::app()->end();
    }
  }

	public function actionAutomove(){
    if (!$combat = Combat::initialization()){
      Yii::app()->end(404);
    } else {
      $combat->setAutomove(true);
      Yii::app()->end();
    }
  }

  public function actionHit(){
    $attack = Yii::app()->request->getPost('attack', null);
    $block  = Yii::app()->request->getPost('block', null);

    $combat = Combat::initialization();
    $round = $combat->getRound(Yii::app()->stat->id);
    $round->setParam($attack, $block);

    if ($combat->isRoundDone()) {
      $combat->nextRound();
    } else {
      $combat->moveComplete();
    }

    echo(json_encode(array(
      'log'   => $combat->getLog(),
      'status' => $combat->status,
      'combat_id' => $combat->combat_id,
      'p1' => $combat->player_1,
      'p2' => $combat->player_2,
      'cur_p' => Yii::app()->stat->id
    )));
  }

  public function actionProblem(){
    $combat = Combat::initialization();

    // Если таймаут
    if (strtotime($combat->time) < time() - 30) {
      $slug = 'enemy_warnings_'.$combat->enemy_id;
      $warnings_count = (int)Yii::app()->session->get($slug);

      $round = $combat->getRound($combat->enemy_id);
      $round->attack = CombatRoundEnum::correctOrRandom();
      $round->block  = CombatRoundEnum::correctOrRandom();
      $round->save(false);

      if (Players::isOnline($combat->enemy_id) && $warnings_count < 3){
        $enemy = Data::enemy($combat->enemy_id);
        $cur_lang = t::iso();

        t::getInstance()->setLanguage($enemy->lang_slug, true);
        Players::SendCMD($combat->enemy_id, 'popup', array(
            'title' => t::get('На вас напали'),
            'text'  => t::get('Игрок %s напал на вас. Хотите управлять боем или включить автобой?', Yii::app()->stat->user),
            'buttons' => array(
                t::encJs('Управлять')  => 'combatManual',
                t::encJs('Автобой')    => 'combatAutomove'
            )
        ));
        t::getInstance()->setLanguage($cur_lang, true);
        Yii::app()->session[$slug] = $warnings_count+1;
      } else {
        Yii::app()->session[$slug] = 0;
        if ($combat->enemy_id == $combat->player_1) {
          $combat->autopass_player_1 = 1;
        } else {
          $combat->autopass_player_2 = 1;
        }
        $combat->save();
      }
      $this->actionHit();
    }
  }
}