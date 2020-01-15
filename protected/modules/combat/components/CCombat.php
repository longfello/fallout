<?php

class CCombat
{
    private $_values = array();
    private $_model;

    public function init()
    {
        foreach ($this->_load()->attributes as $k => $v)
        {
            $this->_values[$k] = $v;
        }

        $this->_values['enemy_id'] = (Yii::app()->stat->id == $this->_values['player_1']) ? $this->_values['player_2'] : $this->_values['player_1'];
    }


    public function __get($key)
    {
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        }
    }


    public function __set($key, $value)
    {
        $this->_values[$key] = $value;

        $this->_load()->$key = $value;
        $this->_load()->save(false);
    }

    private function _load()
    {
        if ($this->_model === null)
        {
            $combatId = Yii::app()->request->getQuery('combatid');
            $this->_model = Combat::model()->findByPk($combatId);
        }

        return $this->_model;
    }

}