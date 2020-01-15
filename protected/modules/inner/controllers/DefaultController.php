<?php

class DefaultController extends ModuleController
{
    public function init()
    {
        //Yii::app()->theme = 'inner';

        //parent::init();
    }


	public function actionRadio()
	{
        $data = array();
		$dataReader = Yii::app()->db->createCommand()
            ->select('c.chat, DATE_FORMAT(FROM_UNIXTIME(c.dt), "%H:%i:%s") AS time, p.user AS user, p.id AS user_id')
            ->from('chat c')
            ->join('players AS p', 'p.id = c.user')
            ->where('p.id = 9')
            ->order('c.id DESC')
            ->limit(4)
            ->query();

        $mf = new MessageFormat();

        while($row = $dataReader->read())
        {
            $row['chat'] = $mf->format($row['chat'], true);
            $row['chat'] = strip_tags($row['chat'], '<img>');

            array_push($data, $row);
        }

        echo CJSON::encode(array('data' => $data));
	}
}