<?php

class CavesbonusController extends CController
{
    public $layout = '//layouts/custom';

    public function actionIndex()
    {
        set_time_limit(0);
        Yii::app()->session;
        $user = Players::model()->findByPk($_SESSION['userid']);
        if ($user->rank != 'Админ')
        {
            $this->redirect('admin.php?error');
        }
        //echo '<pre>' . print_r($_SESSION, 1) . '</pre>';

        $connection = Yii::app()->db;
        $sql = "SELECT
                    lp.coordinate_id, lp.x, lp.y, lp.move, cb.gold,
                    cb.platinum, cb.item_id, cb.item_count
                FROM {{labyrinth_point}} AS lp
                    LEFT JOIN {{caves_bonus}} AS cb
                        ON lp.coordinate_id = cb.coordinate_id";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        $points = $this->_checkMarkedPoint($result);
        $this->render('index', array('coordinates' => $points));

        //echo '<pre>' . print_r($result, 1) . '</pre>';
    }

    private function _checkMarkedPoint($points)
    {
        $markedPoints = array();
        foreach ($points as $one)
        {
            if (array_sum(array($one['gold'], $one['platinum'], $one['item_id'], $one['item_count'])))
            {
                $one['marked'] = 1;
            }
            else
            {
                $one['marked'] = 0;
            }

            $markedPoints[] = $one;
        }

        return $markedPoints;
    }

    public function actionGetBonusbyajax($pointId)
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $model = CavesBonus::model()->findByPk($pointId);

            if (!$model)
            {
                $model = new CavesBonus();
            }

            $this->renderPartial('_modal', array('model' => $model, 'pointId' => $pointId));
            Yii::app()->end();
        }
    }

    public function actionSavebonusbyajax($pointId)
    {
        if (Yii::app()->request->isAjaxRequest && isset($_POST['CavesBonus']))
        {
            $cavesBonus = CavesBonus::model()->findByPk($pointId);

            if (!$cavesBonus)
            {
                $cavesBonus = new CavesBonus();
            }

            $cavesBonus->attributes = $_POST['CavesBonus'];
            $cavesBonus->coordinate_id = (int)$pointId;

            if ($cavesBonus->validate())
            {

                if (!array_sum($_POST['CavesBonus']))
                {
                    CavesBonus::model()->deleteByPk($pointId);
                    $data['del'] = 1;
                }
                else
                {
                    $cavesBonus->save();
                    $data['save'] = 1;
                }
            }
            else
            {
                $errorMessage = array();
                foreach ($cavesBonus->getErrors() as $errors )
                {
                    foreach ($errors as $one)
                    {
                        $errorMessage[] = $one;
                    }
                }

                $data['error'] = implode('<br />', $errorMessage);
            }
        }

        echo json_encode($data);

        Yii::app()->end();
    }
}