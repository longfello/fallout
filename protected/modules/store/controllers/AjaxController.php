<?php

/**
 * Class AjaxController
 */

class AjaxController extends AjaxModuleController {

  const OPERATION_ALL = 'all';
  const OPERATION_CHECKED = 'checked';
  const OPERATION_PROCESS = 'process';

  function actionPut($type, $what){
    if (in_array($type, StoresEnum::$available)){
      if (Yii::app()->request->isPostRequest) {
        switch($type) {
          case StoresEnum::TOXIC_STORE:
            $store = new Toxstore();
            break;
          case StoresEnum::BANK_STORE:
            $store = new Bstore();
            break;
          case StoresEnum::CLAN_STORE:
            $store = new Cstore();
            break;
          default:
            trigger_error('Unknown store type: '. $type);
            die();
        }

        switch($what) {
          case self::OPERATION_ALL:
            $this->putAll($store);
            break;
          case self::OPERATION_CHECKED:
            $this->putAll($store, Yii::app()->request->getPost('items', array()));
            break;
          case self::OPERATION_PROCESS:
            $this->process($store);
            break;
        }
      } else {
        if (in_array($what, array(self::OPERATION_ALL, self::OPERATION_CHECKED))) {
          $this->render('put-'.$what, array(
              'questEquipment' => Questblocks::getCurrentUserQuestEquipment()
          ));
        }
      }
    }
  }

  /**
   * @param $store Toxstore|Bstore|Cstore
   */
  private function process($store){
    $store->process();
    $result = json_encode(array(
        'finished'  => false,
        'percent'   => $store->getMovePercent(),
        'processed' => $store->moveProcessed
    ));

    echo($result);
    Yii::app()->end();
  }

  /**
   * @param $store Toxstore|Bstore|Cstore
   */
  private function putAll($store, $ids=array()){
    $store->putItems($ids);

    $result = json_encode(array(
        'finished'  => false,
        'percent'   => 0,
        'processed' => 0
    ));

    echo($result);
    Yii::app()->end();
  }
}