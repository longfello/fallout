<?php

class ClansController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $pageTitle = 'Управление кланами';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
        'accessControl', // perform access control for CRUD operations
        'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('index', 'delete', 'create', 'update', 'addItem', 'addResource', 'gift', 'remove', 'removeItem', 'removeResource', 'autocompleteItem'),
            'roles' => array('admin'),
        ),
        array('deny',  // deny all users
            'users' => array('*'),
        ),
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model = new Clans;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

    if (isset($_POST['Clans'])) {
      $model->attributes = $_POST['Clans'];
      if ($model->save())
        $this->redirect(array('index'));
    }

    $this->render('create', array(
        'model' => $model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id)
  {
    $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

    if (isset($_POST['Clans'])) {
      $model->attributes = $_POST['Clans'];
      if ($model->save())
        $this->redirect(array('index'));
    }

    $this->render('update', array(
        'model' => $model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
      $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    } else
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
  }

  /**
   * Manages all models.
   */
  public function actionIndex()
  {
    $model = new Clans('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Clans']))
      $model->attributes = $_GET['Clans'];

    $this->render('index', array(
        'model' => $model,
    ));
  }

  public function actionGift($id){
    $model = $this->loadModel($id);
    $this->renderPartial('gift', array('model' => $model));
  }

  public function actionAddItem()
  {
    $request = Yii::app()->request;

    $clanId = (int)$request->getPost('id');
    $itemId   = (int)$request->getPost('itemId');
    $count    = (int)$request->getPost('count', 0);
    $count    = ($count > 0)?$count:1;

    $clan = Clans::model()->findByPk($clanId);
    $ok     = true;

    Yii::app()->getModule('store');
    for($i=0; $i<$count; $i++){
      $model  = new Cstore();
      $model->clan = $clanId;
      $model->item = $itemId;

      $ok &= $model && $model->save();
    }

    if ($ok && $itemId > 0) {

      /** @var $item Equipment */
      $item = Equipment::model()->findByPk($itemId);

      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-success alert-dismissable\">Предмет <b>\"{$item->name}\"</b> ({$count} шт.) успешно выдан в клановую кладовку</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Предмет <b>\"{$item->name}\"</b> ({$count} шт.) успешно выдан в клановую кладовку");
      }

      logdata_admin_give_clan_item::add($clan->owner, $itemId, $count);
      logdata_admin_give_clan_item::add($clan->coowner, $itemId, $count);

      //Кладовщики
      $storage_masters = Players::model()->findAll("clan=:clan_id AND clan_store='Y'", array(":clan_id"=>$clan->id));
      foreach ($storage_masters as $master) {
        logdata_admin_give_clan_item::add($master->id, $itemId, $count);
      }

      //Лог кладовки
      _mysql_exec("INSERT INTO cstore_h(clan,from_player,to_player,item, cnt) VALUES ($clan->id, 0, 0, $item->id, $count)");

      Timeline::add(Timeline::cPLAYER, Timeline::eGIFT, array(
          'message' => "Клану <a href='/admin/clans/update?id={$clan->id}'>{$clan->name}</a> [{$clan->id}] был выдан предмет {$item->name} [{$item->id}]  ({$count} шт.).",
          'admin' => Yii::app()->getUser()->id,
      ), 'Выдача предмета клану', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }
  }

  public function actionAddResource(){
    $request = Yii::app()->request;

    $clanId = (int)$request->getPost('id');
    $gold     = (int)$request->getPost('gold');
    $platinum = (int)$request->getPost('platinum');

    $clan = Clans::model()->findByPk($clanId);
    /** @var $model Clans */
    $clan->gold += $gold;
    $clan->platinum += $platinum;

    if ($clan && $clan->save(false) && ($gold || $platinum)) {
      $resources_msg   = '';
      if ($gold) $resources_msg .= "$gold <b>золота</b>";
      if ($gold && $platinum) $resources_msg .= ", ";
      if ($platinum) $resources_msg .= "$platinum <b>крышек</b>";

      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-success alert-dismissable\">Успешно выдано $resources_msg в казну клана</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Успешно выдано $resources_msg в казну клана");
      }


      $resources   = '';
      if ($gold) $resources .= "<li>золото: $gold</li>";
      if ($platinum) $resources .= "<li>крышки: $platinum</li>";

      if ($gold) {
        logdata_admin_give_clan_resource_gold::add($clan->owner, $gold);
        logdata_admin_give_clan_resource_gold::add($clan->coowner, $gold);
      }
      if ($platinum) {
        logdata_admin_give_clan_resource_platinum::add($clan->owner, $platinum);
        logdata_admin_give_clan_resource_platinum::add($clan->coowner, $platinum);
      }

      //Кладовщики
      $bankers = Players::model()->findAll("clan=:clan_id AND clan_money='Y'", array(":clan_id"=>$clan->id));
      foreach ($bankers as $banker) {
        if ($gold) logdata_admin_give_clan_resource_gold::add($banker->id, $gold);
        if ($platinum) logdata_admin_give_clan_resource_platinum::add($banker->id, $platinum);
      }

      //Лог казны
      _mysql_exec("INSERT INTO cstore_h(clan,from_player,to_player,gold, platinum) VALUES ($clan->id, 0, 0, $gold, $platinum)");

      Timeline::add(Timeline::cPLAYER, Timeline::eGIFT, array(
          'message' => "Клану <a href='/admin/clans/update?id={$clan->id}'>{$clan->name}</a> [{$clan->id}] были выданы ресурсы: <br><ul>$resources</ul>",
          'admin' => Yii::app()->getUser()->id,
      ), 'Выдача ресурсов клану', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }
  }

  public function actionRemove($id){
    $model = $this->loadModel($id);
    $this->renderPartial('remove', array('model' => $model));
  }

  public function actionRemoveItem()
  {
    $request = Yii::app()->request;

    $clanId = (int)$request->getPost('id');
    $itemId = (int)$request->getPost('itemId');
    $count = (int)$request->getPost('count', 0);
    $count = ($count > 0) ? $count : 1;

    $clan = Clans::model()->findByPk($clanId);

    $count_sql = "SELECT
                    COUNT(cs.id)
				  FROM
				    cstore cs
				  WHERE cs.`item`='{$itemId}' AND cs.`clan`='{$clanId}'";
    $items_count = intval(Yii::app()->db->createCommand($count_sql)->queryScalar());

    $count = ($count < $items_count) ? $count : $items_count;

    Yii::app()->getModule('store');
    $res_row = Yii::app()->db->createCommand("DELETE FROM cstore WHERE `item`='{$itemId}' AND `clan`='{$clanId}' LIMIT {$count}")->execute();

    if ($res_row == $count && $itemId > 0) {
      /** @var $item Equipment */
      $item = Equipment::model()->findByPk($itemId);

      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-success alert-dismissable\">Предмет <b>\"{$item->name}\"</b> ({$count} шт.) успешно изъят из клановой кладовки</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Предмет <b>\"{$item->name}\"</b> ({$count} шт.) успешно изъят из клановой кладовки");
      }

      logdata_admin_remove_clan_item::add($clan->owner, $itemId, $count);
      logdata_admin_remove_clan_item::add($clan->coowner, $itemId, $count);

      //Кладовщики
      $storage_masters = Players::model()->findAll("clan=:clan_id AND clan_store='Y'", array(":clan_id" => $clan->id));
      foreach ($storage_masters as $master) {
        logdata_admin_remove_clan_item::add($master->id, $itemId, $count);
      }

      //Лог кладовки
      _mysql_exec("INSERT INTO cstore_h(clan,from_player,to_player,item, cnt) VALUES ($clan->id, 0, 0, $item->id, -$count)");

      Timeline::add(Timeline::cPLAYER, Timeline::eRemove, array(
          'message' => "У клана <a href='/admin/clans/update?id={$clan->id}'>{$clan->name}</a> [{$clan->id}] был изъят предмет {$item->name} [{$item->id}]  ({$count} шт.).",
          'admin' => Yii::app()->getUser()->id,
      ), 'Изъятие предмета клана', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }
  }

  public function actionRemoveResource(){
    $request = Yii::app()->request;

    $clanId = (int)$request->getPost('id');
    $gold     = (int)$request->getPost('gold');
    $platinum = (int)$request->getPost('platinum');

    $clan = Clans::model()->findByPk($clanId);
    /** @var $model Clans */
    $clan->gold -= $gold;
    $clan->platinum -= $platinum;

    if ($clan && $clan->save(false) && ($gold || $platinum)) {

      $resources_msg   = '';
      if ($gold) $resources_msg .= "$gold <b>золота</b>";
      if ($gold && $platinum) $resources_msg .= ", ";
      if ($platinum) $resources_msg .= "$platinum <b>крышек</b>";

      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-success alert-dismissable\">Успешно изъято $resources_msg из казны клана</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Успешно изъято $resources_msg из казны клана");
      }


      $resources   = '';
      if ($gold) $resources .= "<li>золото: $gold</li>";
      if ($platinum) $resources .= "<li>крышки: $platinum</li>";

      if ($gold) {
        logdata_admin_remove_clan_resource_gold::add($clan->owner, $gold);
        logdata_admin_remove_clan_resource_gold::add($clan->coowner, $gold);
      }
      if ($platinum) {
        logdata_admin_remove_clan_resource_platinum::add($clan->owner, $platinum);
        logdata_admin_remove_clan_resource_platinum::add($clan->coowner, $platinum);
      }

      //Кладовщики
      $bankers = Players::model()->findAll("clan=:clan_id AND clan_money='Y'", array(":clan_id"=>$clan->id));
      foreach ($bankers as $banker) {
        if ($gold) logdata_admin_remove_clan_resource_gold::add($banker->id, $gold);
        if ($platinum) logdata_admin_remove_clan_resource_platinum::add($banker->id, $platinum);
      }

      //Лог казны
      _mysql_exec("INSERT INTO cstore_h(clan,from_player,to_player,gold, platinum) VALUES ($clan->id, 0, 0, -$gold, -$platinum)");

      Timeline::add(Timeline::cPLAYER, Timeline::eRemove, array(
          'message' => "Из клана <a href='/admin/clans/update?id={$clan->id}'>{$clan->name}</a> [{$clan->id}] были изъяты ресурсы: <br><ul>$resources</ul>",
          'admin' => Yii::app()->getUser()->id,
      ), 'Изъятие ресурсов клана', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo("<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>");
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }
  }

  public function actionAutocompleteItem($term) {
    $term = addcslashes($term, '%_'); // escape LIKE's special characters
    
    $items = Equipment::model()->findAll(
        'id LIKE :match OR name LIKE :match ORDER BY name',
        array(':match' => "%$term%")
    );

    $result = array();
    foreach ($items as $item) {
      $result[] = array(
        'value'=>$item->id,
        'label'=>$item->name." [".$item->id."]"
      );
    }

    echo json_encode($result);
    Yii::app()->end();
  }
  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id)
  {
    $model = Clans::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'clans-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
