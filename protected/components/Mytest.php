<?php

class Mytest
{
    protected $_values = array();

    public function init()
    {
        //Yii::app()->session;
        $settings = Players::model()->findByPk($_SESSION['userid']);

        foreach ($settings as $key => $row) {
            $this->_values[$key] = $row;
        }
    }

    public function __get($key)
    {
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        }

        return false;
    }
}