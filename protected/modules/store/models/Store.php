<?php

class Store extends CActiveRecord {
  const ITEMS_PUT_LIMIT = 2000;
  const SESSION_PROCESSED_SLUG = 'StoreProcessedCnt';
  public $moveProcessed = 0;
  public $moveCount = 0;

  public function putItems($ids = array()){
    $idsCondition = $ids?" AND u.item IN (".implode(',',$ids).")":"";
    $query = "
select count(u.item)FROM uequipment u
INNER JOIN questblocks q ON q.`owner` = u.`owner` AND q.item = u.item
WHERE u.`owner` = " . Yii::app()->stat->id . " AND u.`status` = '".UequipmentStatusEnum::UNEQUIPPED."' $idsCondition
";
    $count = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryScalar();
    if ($count > 0) {
      GameMessages::flash(GameMessages::MSG_NOTICE, t::get("Нельзя сдавать предметы, на которые есть действующий квест."));
    }

    $this->initMove($ids);
  }

  public function initMove($ids){
    $idsCondition = $ids?" AND u.item IN (".implode(',',$ids).")":"";

    $query = "DELETE FROM uequipment_move WHERE player_id = ".Yii::app()->stat->id;
    Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->execute();

    $query = "
INSERT INTO uequipment_move (id, player_id) (
  select u.id, ".Yii::app()->stat->id." as player_id FROM uequipment u
  LEFT JOIN questblocks q ON q.`owner` = u.`owner` AND q.item = u.item
  WHERE u.`owner` = " . Yii::app()->stat->id . " AND u.`status` = 'U' AND q.id IS NULL $idsCondition
)";
    $this->moveCount = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->execute();
    $this->setProcessed(0);
  }

  public function tableExist($tablename = false){
    $tablename = $tablename?$tablename:$this->tablename;
    $query = "SHOW TABLES LIKE '$tablename';";
    $cmd    = Yii::app()->getDb()->commandBuilder;
    $result = $cmd->createSqlCommand($query)->queryAll();

    return (bool)$result;
  }


  public function addProcessed($value){
    $value += $this->getProcessed();
    $this->setProcessed($value);
  }

  public function setProcessed($value){
    Yii::app()->session[self::SESSION_PROCESSED_SLUG] = $value;
    $this->moveProcessed = $value;
  }

  public function getProcessed(){
    $this->moveProcessed = (int)Yii::app()->session[self::SESSION_PROCESSED_SLUG];
    return $this->moveProcessed;
  }

  public function getMovePercent(){
    return $this->moveCount?round(100*$this->moveProcessed/$this->moveCount):0;
  }
}