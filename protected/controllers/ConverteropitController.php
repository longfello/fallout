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

        Yii::app()->user->setFlash('convertedSuccess', '������ ������� ���������������');

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
         * ������� ���������������� ������
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
         * �������� ���� ������ �� �������
         * @var $command CDbCommand
         */
        $command = Yii::app()->db->createCommand();
        $command->truncateTable('{{experience_worker}}');
    }
}