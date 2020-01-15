<?php

class DefaultController extends ModuleController
{
	public function actionIndex()
	{
        Yii::app()->theme = 'main';

    $achieves = Achieve::model()->findAll();
    /*
        $achieves = Yii::app()->db->createCommand()
            ->select('id, pic, name, desc')
            ->from('{{achieve}}')
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryAll();
*/
		$this->render('index', array('achieves' => $achieves));
	}
}