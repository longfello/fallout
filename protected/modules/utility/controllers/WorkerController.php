<?php

/**
 * Class WorkerController Конвертация данных
 */
class WorkerController extends Controller
{
    public $layout = '//layouts/custom';


    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionConvert()
    {
        set_time_limit(0);
        $this->_truncateTable();


        $convertData = array();
        foreach ($this->_getSourceData() as $one)
        {
            $convertData[] = array(
                'user_id' => $one['id'],
                'garbage' => $one['opit']
            );
            if (count($convertData) == 5000)
            {
                $this->_addConvertedData($convertData);
                $convertData = array();
            }
        }
        $this->_addConvertedData($convertData);

        Yii::app()->user->setFlash('convertedSuccess', t::get('Данные успешно сконвертированы'));

        $this->redirect($this->createUrl('index'));
        $this->render('index');
    }


    private function _getSourceData()
    {
        /**
         * @var $cmd CDbCommand
         */
        $cmd = Yii::app()->db->createCommand()
            ->select('id, opit')
            ->from('players')
            ->where('opit > 0')
            //->limit(50000)
            ->queryAll();

        return $cmd;
    }

    private function _addConvertedData($userData)
    {
        /**
         * Вставка конвертированных данных
         */
        $builder = Yii::app()->db->schema->commandBuilder;
        $builder = $builder->createMultipleInsertCommand('{{experience_worker}}', $userData); // а тут за раз отрпавляем их все в базу
        $builder->execute();
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