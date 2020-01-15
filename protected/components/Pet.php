<?php

class Pet
{
    protected $_values = array();

    public function init()
    {
        $connection = Yii::app()->db;
        $sid = isset($_SESSION['userid'])?$_SESSION['userid']:-1;
        $sql = "
            select
            p.name, p.type, p.exp, p.hp,
            p.level + pt.level level,
            p.max_hp + pt.max_hp max_hp,
            p.ap,
            p.owner,
            p.strength + pt.strength strength,
            p.agility + pt.agility agility,
            p.defense + pt.defense defense,
            pt.w_k*p.strength + pt.w_b*10 add_weight,
            pt.w_k, pt.i_k,
            ROUND(pt.i_b + (p.agility - pt.agility)*pt.i_k/10, 1) add_initiative,
            pt.name type_name, pt.desc, pt.gender, pt.ap as level_ap, pt.id as ptid from pets p join pet_type pt on pt.id=p.type where p.owner=$sid limit 1
        ";
        $command = $connection->createCommand($sql);
        $result = $command->queryRow();

        if ($result)
        {
          $result['type_name'] = t::getDb('name', 'pet_type', 'id', $result['ptid']);
          $result['desc']      = t::getDb('desc', 'pet_type', 'id', $result['ptid']);
          foreach ($result as $key => $value)
          {
              $this->_values[$key] = $value;
          }
        }
    }

    public function __get($key)
    {
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        }
    }
}