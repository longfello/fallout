<?php


class StplayersController extends Controller
{
    public $layout = '//layouts/custom';


    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionConvert()
    {

        $this->_truncateTable();

        $users = $this->_getUsers();


        foreach ($users as $userId)
        {
            $prD = (int)Yii::app()->db->createCommand()
                ->select('SUM(n)')
                ->from('st_players')
                ->where('player = :player and a = :a', array(':player' => $userId['player'], ':a' => 'prD'))
                ->queryScalar();

            $prF = (int)Yii::app()->db->createCommand()
                ->select('SUM(n)')
                ->from('st_players')
                ->where('player = :player and a = :a', array(':player' => $userId['player'], ':a' => 'prF'))
                ->queryScalar();

            $prW = (int)Yii::app()->db->createCommand()
                ->select('SUM(n)')
                ->from('st_players')
                ->where('player = :player and a = :a', array(':player' => $userId['player'], ':a' => 'prW'))
                ->queryScalar();

            $this->_addConvertedData($userId['player'], $this->_convertData($prD), 'prD');
            $this->_addConvertedData($userId['player'], $this->_convertData($prF), 'prF');
            $this->_addConvertedData($userId['player'], $this->_convertData($prW), 'prW');
        }

        Yii::app()->user->setFlash('convertedSuccess', 'Данные успешно конвертированы');

        $this->redirect($this->createUrl('index'));
    }


    private function _getUsers()
    {
        $usersIds = Yii::app()->db->createCommand()
            ->selectDistinct('player')
            ->from('st_players')
            ->queryAll();

        return $usersIds;
    }



    private function _convertData($number)
    {
        /*$intervals = array(0, 25, 75, 175, 375, 755, 1275, 2050, 3050, 4550, 7050);
        $convertedData = null;

        for ($i = 0, $n = count($intervals); $i < $n; $i++)
        {
            if ($number < $intervals[$i])
            {
                $convertedData =  $intervals[$i - 1];
                break;
            }
        }

        if ($convertedData === null)
        {
            $convertedData = array_pop($intervals);
        }

        return $convertedData;
        */
        if ($number < 25) {
            return 0;
        }
        elseif ($number >= 25 && $number < 75) {
            return 25;
        }
        elseif ($number >= 75 && $number < 175) {
            return 75;
        }
        elseif ($number >= 175 && $number < 375) {
            return 175;
        }
        elseif ($number >= 375 && $number < 755) {
            return 355;
        }
        elseif ($number >= 755 && $number < 1275) {
            return 705;
        }
        elseif ($number >= 1275 && $number < 2050) {
            return 755;
        }
        elseif ($number >= 2050 && $number < 3050) {
            return 835;
        }
        elseif ($number >= 3050 && $number < 4550) {
            return 955;
        }
        elseif ($number >= 4550 && $number < 7050) {
            return 1135;
        }
        elseif ($number >= 7050) {
            return 1385;
        }
    }


    private function _addConvertedData($userId, $number, $type)
    {
        /**
         * Вставка конвертированных данных
         * @var $command CDbCommand
         */
        $command = Yii::app()->db->createCommand();
        $command->insert('st_players2', array(
            'dt' => new CDbExpression('NOW()'),
            'player' => $userId,
            'a' => $type,
            'n' => $number
        ));
    }


    private function _truncateTable()
    {
        /**
         * Удаление всех данных из таблицы
         * @var $command CDbCommand
         */
        $command = Yii::app()->db->createCommand();
        $command->truncateTable('st_players2');
    }
}