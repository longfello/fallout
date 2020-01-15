<?php

/**
 * Class AjaxController
 */

class AjaxController extends ModuleController
{
    /** @var Combat $_combatModel */
    private $_combatModel0;
    private $_combatModel;


    /**
     * Сделать ход
     */
    public function actionAttack()
    {
        $combatModel = $this->_combatModel();

        $combat = Combat::initialization();
        if (isset($_POST['CombatRound']))
        {
            $this->_locadModelCombat();


            //$_POST['CombatRound'] = $_POST['autoMove'] ? CombatRound::getRandomValues($_POST['CombatRound']) : $_POST['CombatRound'];

            $model = new CombatRound();
            $model->round = $combat->round;
            $model->attributes = $_POST['CombatRound'];
            $model->combat_id = $combat->combat_id;
            $model->player_id = Yii::app()->stat->id;
            $model->save(false);

            // Установление случайных значений для врага
            if (CombatOption::getEnemyAutoMove())
            {
                $_POST['CombatRound'] = CombatRound::getRandomValues($_POST['CombatRound']);
                $model = new CombatRound();
                $model->round = $combatModel->round;
                $model->attributes = $_POST['CombatRound'];
                $model->combat_id = $combat->combat_id;
                $model->player_id = $combat->enemy_id;
                $model->save(false);
            }

            // Отметить в базе, что игрок сделал ход
            //Combat::makeMove();

            if ($_POST['autoMove']) CombatOption::setAutoMove();

        }

        // Запускаем раунд, если оба игрока сделали ход
        if (CombatRound::isBothPlayersMoved())
        {
            //$this->_combatModel0->is_lock = 1;
            //$this->_combatModel0->save(false);

            $attackData = CombatRound::getAttackData($combatModel->round);

            // Данные о текущем игроке
            //$playerOne = Yii::app()->stat;
            // Данные о враге
            //$playerTwo = Data::enemy(Yii::app()->combat->enemy_id);

            // Текуший игрок атакует второго
            if ($attackData['playerOne'])
            {
                $msg = RBattle::attack(Yii::app()->stat->id, $combat->enemy_id);
            }
            else
            {
                $msg = RBattle::failedAttack(Yii::app()->stat->id, $combat->enemy_id);
            }
            CombatRound::addLogMessage(Yii::app()->stat->id, $msg, $combatModel->round);

            // Второй игрок атакует текущего
            $combatModel = $this->_combatModel(true);
            if ($combatModel->status == 'active')
            {
                if ($attackData['playerTwo'])
                {
                    $msg = RBattle::attack($combat->enemy_id, Yii::app()->stat->id);
                }
                else
                {
                    $msg = RBattle::failedAttack($combat->enemy_id, Yii::app()->stat->id);
                }
                CombatRound::addLogMessage($combat->enemy_id, $msg, $combatModel->round);

            }


            CombatOption::setRefresh();
            $combat->time = new CDbExpression('NOW()');
            $combat->round++;

        }
    }


    /**
     * Запрос на обновление страницы
     */
    public function actionRefresh($combatid)
    {
        $data['isRefresh'] = CombatOption::isRefresh($combatid);
        $data['roundElapsedSeconds'] = Combat::getRoundElapsedSeconds($combatid);
        $data['autoMove'] = CombatOption::getPlayerAutoMove($combatid);
        $data['status'] = Combat::isActivated($combatid);
        echo CJSON::encode($data);
    }



    /**
     * Получить html-блок с раундами
     */
    public function actionGetRounds()
    {
        //file_put_contents(Yii::getPathOfAlias('webroot') . '/log.txt', date("Y-m-d H:i:s") . "\n" . print_r($_SESSION, 1), FILE_APPEND . "\n");

        //Combat::setAutopass(Yii::app()->request->getQuery('autopassCurrent'));

        Combat::updateSessionData();

        //$_SESSION['combat']['autopass_current'] = Yii::app()->request->getQuery('isEnableWay');

        $lastRound = Yii::app()->request->getQuery('lastRound');

        // Получить все раунды боя
        $combatRounds = CombatRound::getAll($lastRound);

        // Сгруппировать раунды
        $combatRounds = CombatRound::groupRounds($combatRounds);

        $roundView = $this->makeRoundView($combatRounds, $lastRound);

        echo CJSON::encode($roundView);
    }


    /**
     * Создание представления
     * @param $combatRounds
     * @return array
     */
    private function makeRoundView($combatRounds)
    {
        $rounds = array();

        if ($combatRounds)
        {
            foreach ($combatRounds as $number => $round)
            {
                //$rounds[] = $this->renderPartial('_round', array('model' => $round, 'enemyLog' => CombatRound::getEnemyLog($round->round)), true);
                $rounds[] = $this->renderPartial('_round', array('round' => $round, 'number' => $number), true);
            }

            $data['lastRound'] = $_SESSION['combat']['round'] - 1;
            $data['isEnabledWay'] = 1;
        }

        $data['rounds'] = $rounds;

        // Если бой уже закончен
        if ($_SESSION['combat']['win_log'])
        {
            $data['winLog'] = $_SESSION['combat']['win_log'];
        }

        $data['autopassCurrent'] = $_SESSION['combat']['autopass_current'];
        $data['status'] = $_SESSION['combat']['status'];

        $data['roundSecondLeft'] = $_SESSION['combat']['round_second_left'];

        return $data;
    }


    private function _locadModelCombat()
    {
         $this->_combatModel0 = $this->_combatModel();
    }

    private function _combatModel($reload = false)
    {
        if ($this->_combatModel === null || $reload)
        {
          return Combat::initialization();
        }
        else
        {
            return $this->_combatModel;
        }
    }
}