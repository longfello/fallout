<?php

class Eq
{
    protected $_values = array();

    public function init()
    {
        if (Yii::app()->stat->model) {
            $connection = Yii::app()->db;
            /*
            $sql = "select
            ifnull(sum( IF( u.status='E', e.add_max_hp, 0 )),0) add_max_hp,
            ifnull(sum( IF( u.status='E', e.add_max_energy, 0 )),0) add_max_energy,
            ifnull(max( IF( u.status='E' AND e.type='A', e.id, 0 )),0) a_id,
            ifnull(max( IF( u.status='E' AND e.type='W', e.id, 0 )),0) w_id,
            ifnull(max( IF( u.status='E', e.eprot, 0 )),0) eprot,
            ifnull(sum( IF( u.status='E', e.add_strength, 0 )),0) add_strength,
            ifnull(sum( IF( u.status='E', e.add_agility, 0 )),0) add_agility,
            ifnull(sum( IF( u.status='E', e.add_defense, 0 )),0) add_defense,
            ifnull(sum( IF( u.status='E' AND e.type='A', e.add_strength , 0 )),0) add_a_strength,
            GREATEST(0,ifnull(sum( IF( u.status='E' AND e.type='A' AND e.minlev >= 80 OR e.id=115, 0, e.weight) ),0)) weight
            from uequipment u join equipment e on e.id=u.item
            where u.status in ('E','U') and u.owner= ".Yii::app()->stat->model->id;
            */

            $sql = "select
                    (
                        SELECT COALESCE(sum(add_max_hp),0) 
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) add_max_hp,
                    (
                        SELECT COALESCE(sum(add_max_energy),0)   
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) add_max_energy,
                    (
                        SELECT COALESCE(max(e.id),0)   
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item AND e.type='A'
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) a_id,
                    (
                        SELECT COALESCE(max(e.id),0)   
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item AND e.type='W'
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) w_id,
                    (
                        SELECT COALESCE(max(e.eprot),0)   
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) eprot,
                    (
                        SELECT COALESCE(sum(add_strength),0)
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) add_strength,
                    (
                        SELECT COALESCE(sum(add_agility),0)  
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) add_agility,
                    (
                        SELECT COALESCE(sum(add_defense),0)   
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) add_defense,
                    (
                        SELECT COALESCE(sum(add_strength),0)   
                        FROM uequipment u
                        LEFT JOIN equipment e on e.id=u.item AND e.type='A'
                        WHERE u.owner = '".Yii::app()->stat->model->id."' AND u.status = 'E'
                    ) add_a_strength,
                    (
                        SELECT sum(weight * cnt)
                        FROM equipment e
                        LEFT JOIN
                        (
                            SELECT item, count(item) cnt
                            FROM uequipment
                            WHERE owner = '".Yii::app()->stat->model->id."'
                            GROUP BY item
                        ) ue ON e.id = ue.item
                    ) weight
                    ";
            $command = $connection->createCommand($sql);
            $result = $command->queryRow();

            foreach ($result as $key => $value)
            {
                $this->_values[$key] = $value;
            }
            // Получить текущий вес
            $this->_values['weight'] = $this->_values['weight'] / 10;
        } else {
            Yii::app()->request->redirect('/');
            Yii::app()->end();
        }


    }

    public function __get($key)
    {
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        }
    }

}