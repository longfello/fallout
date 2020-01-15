<?php

class Data
{
    /**
     * Получить данные о враге
     * @param $id
     * @return Players
     */
    public static function enemy($id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'id = :id';
        $criteria->params = array(':id' => $id);

        return Players::model()->find($criteria);
    }
}