<?php

class Test2
{
    public static function getHp()
    {
        Yii::app()->mytest->energy += 1000;
        echo Yii::app()->mytest->energy;
    }
}