<?php

class ConverteropitController extends Controller
{
    public $layout = '//layouts/custom';


    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionConvert()
    {

        $this->_truncateTable();


        foreach ($this->_getSourceData() as $one)
        {
            if ($one['opit'] > 0)
            {
                $this->_addConvertedData($one);
            }
        }

        Yii::app()->user->setFlash('convertedSuccess', 'Данные успешно сконвертированы');

        $this->redirect($this->createUrl('index'));
    }


    private function _getSourceData()
    {
        /**
         * @var $cmd CDbCommand
         */
        $cmd = Yii::app()->db->createCommand()
            ->select('id, opit')
            ->from('players')
            ->queryAll();

        return $cmd;
    }

    private function _addConvertedData($userData)
    {
        /**
         * Вставка конвертированных данных
         * @var $command CDbCommand
         */
        $command = Yii::app()->db->createCommand();
        $command->insert('{{experience_worker}}', array(
            'user_id' => $userData['id'],
            'garbage' => $userData['opit']
        ));
    }


    private function _truncateTable()
    {
        /**
         * Удаление всех данных из таблицы
         * @var $command CDbCommand
         */
        $command = Yii::app()->db->createCommand();
        $command->truncateTable('{{experience_worker}}');
    }
}