<?php

/*
 * Получить с статы игрока
 */

/**
 * Class Stat
 * @property $id
 * @property Players $model
 */
class Stat
{
    protected $_values = array();
    /** @var Players */
    public $model=null;

    public function init()
    {
        // Редирект на главную, если игрок не авторизован
        if (!isset($_SESSION['userid'])) {
          if (isset(Yii::app()->controller->public) && !Yii::app()->controller->public){
              Yii::app()->request->redirect('/');
              Yii::app()->end();
              return false;
          }
        } else {
            $this->_loadModel();
        }
    }

    public function __get($key){
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        } else return null;
    }

    public function _loadModel($id = false){
    	if (!$id) {
		  $id = isset($_SESSION['userid'])?$_SESSION['userid']:-1;
    	}

	    $connection = Yii::app()->db;
	    $sql = "select p.*, ifnull(o.tokens,0) tokens, TIMESTAMPDIFF(DAY, `unv_start`, NOW()) as `unv_elapsed_time`, UNIX_TIMESTAMP(`last_visit_toxic_caves`) AS `last_visit_toxic_caves` from players p left join outposts o on o.owner=p.id where p.id={$id} limit 1";
	    $command = $connection->createCommand($sql);
        $result = $command->queryRow();
        if ($result) {
            foreach ($result as $key => $value)
            {
                $this->_values[$key] = $value;
            }

//            if (!$this->model) {
                $this->model = Players::model()->findByPk($id);
//            }
            $this->_values['model'] = $this->model;
        } else {
            unset($_SESSION['userid']);
            session_unset();
            session_destroy();
            Yii::app()->request->redirect('/');
            Yii::app()->end();
            return false;
        }

    }
}