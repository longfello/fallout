<?php

class PlayersController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $pageTitle = 'Управление игроками';

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
        array('allow',
          'actions' => array('index', 'delete', 'create', 'update', 'analytics', 'addItem', 'removeItem', 'portCity', 'portCave', 'mults', 'bot', 'mail', 'ac', 'mailSend', 'deleteBanRule', 'editBanRule', 'createBanRule', 'toggleBanRule', 'gift', 'remove', 'addResource', 'removeResource', 'autocompleteItem', 'login', 'gifts', 'giftsSend'),
            'roles' => array('admin'),
        ),
        array('allow',
            'actions' => array('index', 'update', 'analytics', 'portCity', 'portCave', 'mults', 'bot', 'editBanRule', 'createBanRule', 'toggleBanRule', 'ac'),
            'roles' => array('Модератор', 'Чат-модератор'),
        ),
        array('deny',  // deny all users
            'users' => array('*'),
        ),
    );
  }

  public function actionLogin($id){
    $player = Players::model()->findByPk($id);
    if ($player){
      $_SESSION['userid'] = $player->id;
      $_SESSION['user'] = $player->user;
      $_SESSION['pass'] = $player->pass2;
      $_SESSION['beginner_window'] = true;
      $this->redirect('/city.php');
    }
    $this->redirect(Yii::app()->request->urlReferrer);
  }

  public function actionAc(){
    $q        = Yii::app()->request->getParam('q', false);
    $q        = addcslashes($q, '%_');
    $criteria = new CDbCriteria();
    $criteria->addCondition("user LIKE :login");
    $criteria->params[':login'] = "%{$q}%";
    $criteria->select = "id, user";
    $criteria->limit = 15;
    $models   = Players::model()->findAll($criteria);
    $ret      = array();
    foreach($models as $one) {
      $ret[] = array(
        'value' => $one->id,
        'label' => $one->user
      );
    }

    echo CJavaScript::jsonEncode($ret);
    Yii::app()->end();
  }
  public function actionMailSend(){
    $info  = Yii::app()->request->getPost('info', array());
    $model = new MailForm() ;
    $model->attributes = $info;

    $ids   = Yii::app()->request->getPost('ids', array());
    
    switch ($model->send_type) {
      case 'email'://Email
       foreach($ids as $id){
          $player = Players::model()->findByPk($id);
          if ($player && $player->email_confirmed == Players::YES){
	          $email = new Email();
	          $email->from_email = 'support@revival.online';
	          $email->from_name  = 'revival.online';
	          $email->to_email   = $player->email;
	          $email->to_name    = $player->user;
	          $email->subject    = "{$player->user}, ". (($player->lang_slug=='ru')?$model->title:$model->title_en);
	          $email->body       = ($player->lang_slug=='ru')?$model->content:$model->content_en;
	          $email->save();
          }
        }
        break;
      case 'gmail'://Игровая почта
        foreach($ids as $id){
          $player = Players::model()->findByPk($id);
          if ($player) {

            $mailbox = new Mail();

            $mailbox->senderid = $model->sender_id;
            $mailbox->owner = $id;

            if ($player->lang_slug == 'ru') {
              $mailbox->sender = $model->sender;
              $mailbox->subject = $model->title;
              $mailbox->body = $model->content;
            } else {
              $mailbox->sender = $model->sender_en;
              $mailbox->subject = $model->title_en;
              $mailbox->body = $model->content_en;
            }

            $mailbox->save();
          }
        }
        break;
      case 'log'://Игровой лог
        foreach($ids as $id) {
          logdata_admin_send_log::add($id, $model->content, $model->content_en, $model->sender, $model->sender_en);
        }
        break;
    }
  }
  public function actionMail(){

    $this->pageTitle .= ' - почтовая рассылка';

    $model = new MailForm;

    if (isset($_POST['MailForm'])) {
      $model->attributes = $_POST['MailForm'];
      // validate user input and redirect to the previous page if valid
      if ($model->validate()) {
        // $this->redirect(Yii::app()->getModule('admin')->user->returnUrl);
        // Yii::app()->user->setFlash('success','Сообщения были отправлены!');
        $this->render('mail-send', array(
          'model' => $model,
        ));
        return;
      }
    }

    $this->render('mail', array(
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
    Yii::app()->getModule('chat');
    $this->pageTitle .= ' - редактирование';
    $model = $this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Players'])) {
      $oldBasicData = isset($_POST['oldData'])?$_POST['oldData']:CJavaScript::jsonEncode($model->getBasicData());
      $oldBasicData = (array)CJavaScript::jsonDecode($oldBasicData);
      $data = $_POST['Players'];
      foreach ($data as $key => $value){
        if ($model->hasAttribute($key)){
          $model->setAttribute($key, $value);
        }
      }
      if ($model->save()) {
        if (array_diff_assoc($oldBasicData, $model->getBasicData())) {
          TimelineEvent::playerChanged($model, $oldBasicData);
        }
        $meta = (isset($_POST['Players']['meta']))?$_POST['Players']['meta'] : array();
        foreach ($meta as $key => $value) {
          $model->setMeta($key, $value);
        }

        if (isset($_POST['user_chat_avatar'])) {
            $cur_avatar = ChatAvatar::model()->findByAttributes(['player_id' => $id]);
            if (!$cur_avatar) {
                $cur_avatar = new ChatAvatar();
                $cur_avatar->player_id = $id;
            }
            $cur_avatar->avatar_id = (int)$_POST['user_chat_avatar'];
            $cur_avatar->save();
        }
        $this->redirect(array('index'));
      }
    }

    if (Yii::app()->request->getPost('action', 'update') == 'remove-outpost') {
        if ($model->outpost){
	        $model->outpost->delete();
	        $this->redirect(array('update','id'=>$model->id));
        }
    }

	  if(isset($_POST['create-outpost'])){

      if (!$model->outpost){
	      $model->outpost = new Outposts();
	      $model->outpost->owner = $id;
	      $model->outpost->save();
	      $this->redirect(array('update','id'=>$model->id));
      }

    }
	  if(isset($_POST['Outposts'])){
	    $outpost = $model->outpost;
	    $outpost->attributes=$_POST['Outposts'];
		  if($outpost->save())
			  $this->redirect(array('update','id'=>$model->id));
	  }


    $cur_avatar = ChatAvatar::model()->findByAttributes(['player_id' => $id]);
    $avatars = ChatAvatarBase::model()->findAll('owner=0 OR owner=:u1', array(':u1'=>$id));
    $this->render('update', array(
        'model' => $model,
        'chatavatar' => $cur_avatar->avatar_id,
        'chatavatarimage' => $cur_avatar->base->image,
        'avatars' => $avatars,
    ));
  }
  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
  }
  /**
   * Manages all models.
   */
  public function actionIndex()
  {
    $model = new Players('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Players']))
      $model->attributes = $_GET['Players'];

    $this->render('admin', array(
        'model' => $model,
    ));
  }

  public function actionIndextest()
  {
    $model = new Players('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Players']))
      $model->attributes = $_GET['Players'];

    $this->render('admin2', array(
      'model' => $model,
    ));
  }
  /**
   * Телепортировать игрока в город
   */
  public function actionPortCity()
  {
    $id = (int)Yii::app()->request->getPost('id');

    $players = Players::model()->findByPk($id);
	  $players->labyrinth_x = 0;
	  $players->labyrinth_y = 0;
	  $players->x = 0;
    $players->y = 0;
    $players->travel_place = '/pustosh.php';
    if ($players->save(false)) {
      Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Телепортация в город прошла успешно");
    } else {
      Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
    }

    $this->redirect(array('update', 'id' => $id));
  }
  /**
   * Телепортировать игрока в пещеры
   */
  public function actionPortCave()
  {
    $id = (int)Yii::app()->request->getPost('id');

    $players = Players::model()->findByPk($id);
    $players->x = -45;
    $players->y = 47;
	  $players->labyrinth_x = 0;
	  $players->labyrinth_y = 0;
    $players->travel_place = '/caves.php';
    if ($players->save(false)) {
      Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Телепортация в пещеры прошла успешно");
    } else {
      Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
    }

    $this->redirect(array('update', 'id' => $id));
  }
  /**
   * Добавить предмет в инвенртар
   */
  public function actionAddItem()
  {
    $request = Yii::app()->request;

    $playerId = (int)$request->getPost('id');
    $itemId   = (int)$request->getPost('itemId');
    $place    = $request->getPost('place');
    $count    = (int)$request->getPost('count', 0);
    $count    = ($count > 0)?$count:1;

    $player = Players::model()->findByPk($playerId);
    $ok     = true;

    for($i=0; $i<$count; $i++){
      $place_text = '';
      $place_log = '';
      $model = false;
      switch($place){
        case 'inv':
          $model = new Uequipment();
          $model->owner = $playerId;
          $model->item = $itemId;
          $place_text = 'на руки';
          break;
        case 'store':
          Yii::app()->getModule('store');
          $model  = new Bstore();
          $model->player = $playerId;
          $model->item = $itemId;
          $place_text = 'в кладовку';
          break;
        case 'clan':
          if ($player->clan) {
            Yii::app()->getModule('store');
            $model  = new Cstore();
            $model->clan = $player->clan;
            $model->item = $itemId;
            $place_text = 'в клановую кладовку';
          }
          break;
      }
      $ok &= $model && $model->save();
    }

    if ($ok && $itemId > 0) {
      if (Yii::app()->request->isAjaxRequest) {
        echo json_encode(array(
            'html'=>"<div class=\"alert alert-success alert-dismissable\">Предмет успешно выдан $place_text ({$count} шт.)</div>")
        );
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Предмет успешно выдан $place_text ({$count} шт.)");
      }
      logdata_admin_give_item::add($player->id, $place, $itemId, $count);
      $item = Equipment::model()->findByPk($itemId);
      /** @var $item Equipment */
      Timeline::add(Timeline::cPLAYER, Timeline::eGIFT, array(
        'message' => "Игроку <a href='/admin/players/update?id={$player->id}'>{$player->user}</a> [{$player->id}] был выдан {$place_text} предмет {$item->name} [{$item->id}]  ({$count} шт.).",
        'admin' => Yii::app()->getUser()->id,
      ), 'Выдача предмета игроку', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo json_encode(array(
            'html'=>"<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>")
        );
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }
    if (!Yii::app()->request->isAjaxRequest) {
      $this->redirect($this->createUrl('update', array('id' => $playerId)));
    }
  }
  public function actionAddResource(){
    $request = Yii::app()->request;

    $playerId = (int)$request->getPost('id');
    $gold     = (int)$request->getPost('gold');
    $platinum = (int)$request->getPost('platinum');
    $water    = (int)$request->getPost('water');
    $place    = $request->getPost('place');
    $player = Players::model()->findByPk($playerId);

    $place_text = '';
    $model = false;
    switch($place){
      case 'hand':
        $model = Players::model()->findByPk($playerId);
        /** @var $model Players */
        $model->gold += $gold;
        $model->platinum += $platinum;

        if ($water>0) {
          $criteria = new CDbCriteria();
          $criteria->addCondition("owner = :id");
          $criteria->params[':id'] = $playerId;

          $outpost_model = Outposts::model()->find($criteria);
          if ($outpost_model) {
            $outpost_model->tokens += $water;
            $outpost_model->save();
          }
        }

        $place_text = 'на руки';
        break;
      case 'bank':
        $model = Players::model()->findByPk($playerId);
        /** @var $model Players */
        $model->bank += $gold;
        $place_text = 'в банк';
        break;
      case 'toxbank':
        $model  = Toxbank::model()->findByPk($playerId);
        if (!$model) {
          $model = new Toxbank();
          $model->player_id = $playerId;
        }
        $model->deposited_gold += $gold;
        $place_text = 'в банк токсических пещер';
        break;
      case 'clan':
        if ($player->clan) {
          $model = Clans::model()->findByPk($player->clan);
          /** @var $model Clans */
          $model->gold += $gold;
          $model->platinum += $platinum;
        }
        $place_text = 'в казну клана';
        break;
    }


    if ($model && $model->save(false) && ($gold || $platinum || $water)) {
      $player->refresh();
      if (Yii::app()->request->isAjaxRequest) {

        echo json_encode(array(
            'html'=>"<div class=\"alert alert-success alert-dismissable\">Ресурсы $place_text успешно выданы</div>",
            'gold_inv'=>$player->gold,
            'gold_bank'=>$player->bank,
            'gold_cave'=>$player->getToxicGold(),
            'platinum'=>$player->platinum,
            'water'=>$player->getWater()
        ));
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Ресурсы $place_text успешно выданы");
      }

      $resources   = '';
      if ($gold) $resources .= "<li>золото: $gold</li>";
      if ($platinum && $place=='hand') $resources .= "<li>крышки: $platinum</li>";
      if ($water && $place=='hand') $resources .= "<li>вода: $water</li>";

      if ($gold) logdata_admin_give_resource_gold::add($player->id, $place, $gold);
      if ($platinum && $place=='hand') logdata_admin_give_resource_platinum::add($player->id, $platinum);
      if ($water && $place=='hand') logdata_admin_give_resource_water::add($player->id, $water);
      Timeline::add(Timeline::cPLAYER, Timeline::eGIFT, array(
        'message' => "Игроку <a href='/admin/players/update?id={$player->id}'>{$player->user}</a> [{$player->id}] были выданы ресурсы {$place_text}: <br><ul>$resources</ul>",
        'admin' => Yii::app()->getUser()->id,
      ), 'Выдача ресурсов игроку', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo json_encode(array(
            'html'=>"<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>")
        );
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }
  }

    public function actionRemoveItem()
    {
        $request = Yii::app()->request;

        $playerId = (int)$request->getPost('id');
        $itemId = (int)$request->getPost('itemId');
        $place = $request->getPost('place');
        $count = (int)$request->getPost('count', 0);
        $count = ($count > 0) ? $count : 1;

        $player = Players::model()->findByPk($playerId);

        $res_row = 0;

        $place_text = '';
        switch ($place) {
            case 'inv':
                $count_sql = "SELECT
									COUNT(ue.id)
								  FROM
									uequipment ue
								  WHERE ue.`item`='" . $itemId . "' AND ue.owner='" . $playerId . "'";
                $items_count = intval(Yii::app()->db->createCommand($count_sql)->queryScalar());
                $count = ($count < $items_count) ? $count : $items_count;

                $res_row = Yii::app()->db->createCommand("DELETE FROM uequipment WHERE `item`='{$itemId}' AND `owner`='{$playerId}' LIMIT {$count}")->execute();
                $place_text = 'инвентаря';
                break;
            case 'store':
                $count_sql = "SELECT
									COUNT(bs.id)
								  FROM
									bstore bs
								  WHERE bs.`item`='" . $itemId . "' AND bs.player='" . $playerId . "'";
                $items_count = intval(Yii::app()->db->createCommand($count_sql)->queryScalar());

                $count = ($count < $items_count) ? $count : $items_count;

                $res_row = Yii::app()->db->createCommand("DELETE FROM bstore WHERE `item`='{$itemId}' AND `player`='{$playerId}' LIMIT {$count}")->execute();
                $place_text = 'кладовки';
                break;
        }

        if ($res_row == $count && $itemId > 0) {

            logdata_admin_remove_item::add($player->id, $place, $itemId, $count);
            $item = Equipment::model()->findByPk($itemId);

            /** @var $item Equipment */
            Timeline::add(Timeline::cPLAYER, Timeline::eRemove, array(
                'message' => "У игрока <a href='/admin/players/update?id={$player->id}'>{$player->user}</a> [{$player->id}] из {$place_text} был изят предмет {$item->name} [{$item->id}]  ({$count} шт.).",
                'admin' => Yii::app()->getUser()->id,
            ), 'Изъятие предмета игрока', '<i class="fa fa-money bg-blue"></i>');

            if (Yii::app()->request->isAjaxRequest) {
                echo json_encode(array(
                        'html' => "<div class=\"alert alert-success alert-dismissable\">Предмет {$item->name} [{$item->id}] успешно изъят из $place_text ({$count} шт.)</div>")
                );
            } else {
                Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Предмет {$item->name} [{$item->id}] успешно изъят из $place_text ({$count} шт.)");
            }
        } else {
            if (Yii::app()->request->isAjaxRequest) {
                echo json_encode(array(
                        'html' => "<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>")
                );
            } else {
                Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
            }
        }
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect($this->createUrl('update', array('id' => $playerId)));
        }
    }

  public function actionRemoveResource(){
    $request = Yii::app()->request;

    $playerId = (int)$request->getPost('id');
    $gold     = (int)$request->getPost('gold');
    $platinum = (int)$request->getPost('platinum');
    $water    = (int)$request->getPost('water');
    $place    = $request->getPost('place');
    $place_text = '';
    $model = false;

    $player = Players::model()->findByPk($playerId);
    /** @var $player Players */

    switch($place){
      case 'hand':
        $model = Players::model()->findByPk($playerId);
        $model->gold -= $gold;
        $model->platinum -= $platinum;

        if ($water>0) {
          $criteria = new CDbCriteria();
          $criteria->addCondition("owner = :id");
          $criteria->params[':id'] = $playerId;

          $outpost_model = Outposts::model()->find($criteria);
          if ($outpost_model) {
            $outpost_model->tokens -= $water;
            $outpost_model->save();
          }
        }
        $place_text = 'из инвентаря';
        break;
      case 'bank':
        $model = Players::model()->findByPk($playerId);
        $model->bank -= $gold;
        $place_text = 'из банка';
        break;
      case 'toxbank':
        $model  = Toxbank::model()->findByPk($playerId);
        if (!$model) {
          $model = new Toxbank();
          $model->player_id = $playerId;
        }
        $model->deposited_gold -= $gold;
        $place_text = 'из банка токсических пещер';
        break;
    }

    if ($model && $model->save(false) && ($gold || $platinum || $water)) {
      $player->refresh();

      $resources   = '';
      if ($gold) $resources .= "<li>золото: $gold</li>";
      if ($platinum && $place=='hand') $resources .= "<li>крышки: $platinum</li>";
      if ($water && $place=='hand') $resources .= "<li>вода: $water</li>";

      if ($gold) logdata_admin_remove_resource_gold::add($player->id, $place, $gold);
      if ($platinum && $place=='hand') logdata_admin_remove_resource_platinum::add($player->id, $platinum);
      if ($water && $place=='hand') logdata_admin_remove_resource_water::add($player->id, $water);

      if (Yii::app()->request->isAjaxRequest) {
        echo json_encode(array(
          'html'=>"<div class=\"alert alert-success alert-dismissable\">Успешно изъято {$place_text} <ul>$resources</ul></div>",
          'gold_inv'=>$player->gold,
          'gold_bank'=>$player->bank,
          'gold_cave'=>$player->getToxicGold(),
          'platinum'=>$player->platinum,
          'water'=>$player->getWater()
        ));
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_SUCCESS, "Успешно изъято {$place_text} <ul>$resources</ul>");
      }

      Timeline::add(Timeline::cPLAYER, Timeline::eRemove, array(
          'message' => "У игрока <a href='/admin/players/update?id={$player->id}'>{$player->user}</a> [{$player->id}] были изьяты ресурсы {$place_text}: <br><ul>$resources</ul>",
          'admin' => Yii::app()->getUser()->id,
      ), 'Изятие ресурсов игрока', '<i class="fa fa-money bg-blue"></i>');
    } else {
      if (Yii::app()->request->isAjaxRequest) {
        echo json_encode(array(
            'html'=>"<div class=\"alert alert-danger alert-dismissable\">Неправильно заполнены поля</div>")
        );
      } else {
        Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильно заполнены поля');
      }
    }

  }

  public function actionMults()
  {
    $this->pageTitle .= ' - список мультов';

    $query = "
SELECT ip, count(id) AS count, GROUP_CONCAT(id) AS ids
FROM players
WHERE ip <> 0
GROUP BY ip
HAVING count(id) > 1
ORDER BY count(id) DESC";
    $res = Yii::app()->db->commandBuilder->createSqlCommand($query)->queryAll();

    $this->render('mults', array(
        'datas' => $res,
    ));
  }
  public function actionAnalytics($id)
  {
    $this->pageTitle .= ' - аналитика';

    $model = $this->loadModel($id);
    /** @var $model Players */

    $criteria = new CDbCriteria();
    $criteria->addCondition("player_id = :id");
    $criteria->addCondition("(`until` > NOW() OR `until` IS NULL)");
    $criteria->params[':id'] = $model->id;
    $bots = BannedPlayers::model()->findAll($criteria);

    $criteria = new CDbCriteria();
    $criteria->order = "created DESC";
    $criteria->addCondition("player_id = :id");
    $criteria->addCondition("`until` < NOW()");
    $criteria->params[':id'] = $model->id;
    $old_bots = BannedPlayers::model()->findAll($criteria);
    
    $isBlockedByIP = Ban::checkIp($model->ip);

    $criteria = new CDbCriteria();
    $criteria->order = "date DESC";
    $criteria->addCondition("user_id = :user_id");
    $criteria->params[':user_id'] = $model->id;
    $criteria->limit = 100;

    $botData = Antibot::model()->findAll($criteria);
    $botCheckEnabled = (bool)$model->logme;

    $criteria = new CDbCriteria();
    $criteria->addCondition("id <> :id");
    $criteria->params[':id'] = $id;
    $mults = Players::model()->findAllByAttributes(array('ip' => $model->ip), $criteria);

    $referals = Players::model()->findAllByAttributes(array('ref' => $model->id));

    $this->render('analytics', array(
      'model' => $model,
      'bots' => $bots,
      'old_bots' => $old_bots,
      'blockedByIP' => $isBlockedByIP,
      'botData' => $botData,
      'botCheckEnabled' => $botCheckEnabled,
      'mults' => $mults,
      'refs' => $referals
    ));
  }
  public function actionCreateBanRule(){
    $id  = Yii::app()->request->getParam('id', -1);
    $player = Players::model()->findByPk($id);

    if ($player) {

      $model = new BannedPlayers;
      $model->player_id = $id;
      $model->login = $player->user;
      $model->admin_id = Yii::app()->getUser()->id;

      if ($model) {
        // uncomment the following code to enable ajax-based validation
        if(isset($_POST['ajax']) && $_POST['ajax']==='banned-players-updateBan-form')
        {
          echo CActiveForm::validate($model);
          Yii::app()->end();
        }

        if(isset($_POST['BannedPlayers']))
        {
          $model->attributes=$_POST['BannedPlayers'];
          if($model->validate())
          {
            // form inputs are valid, do something here
            $model->save();
            $this->redirect($this->createUrl('analytics', array('id' => $model->player_id)));
            return;
          }
        }

        $this->render('updateBan', array('model' => $model));
      }

    }
  }
  public function actionEditBanRule(){
    $id  = Yii::app()->request->getParam('id', -1);

    $model = BannedPlayers::model()->findByPk($id);

    if ($model) {
      // uncomment the following code to enable ajax-based validation
      if(isset($_POST['ajax']) && $_POST['ajax']==='banned-players-updateBan-form')
      {
          echo CActiveForm::validate($model);
          Yii::app()->end();
      }

      if(isset($_POST['BannedPlayers']))
      { 
        $model->attributes=$_POST['BannedPlayers'];
        if($model->validate())
        {
          // form inputs are valid, do something here
          $model->save();
          $this->redirect($this->createUrl('analytics', array('id' => $model->player_id)));
          return;
        }
      }

      $this->render('updateBan', array('model' => $model));
    }
  }
  public function actionDeleteBanRule(){
    $id = Yii::app()->request->getParam('id', -1);
    BannedPlayers::model()->deleteByPk($id);
    $this->redirect(Yii::app()->request->urlReferrer);
  }

  public function actionToggleBanRule(){
    $id = Yii::app()->request->getParam('id', -1);
    $type = Yii::app()->request->getParam('type', false);
    $model = BannedPlayers::model()->findByPk($id);

    if (Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics_ban_'.$type))) {
      if ($model) {
        $param = 'active_'.$type;
        $model->{$param} = ($model->{$param}==1)?0:1;
        $model->save();
      }
    }


    $this->redirect($this->createUrl('analytics', array('id' => $model->player_id)));
  }
  public function actionBot($id){
    if (Yii::app()->request->isPostRequest) {
      $model = $this->loadModel($id);
      if ($model) {

        $action = Yii::app()->request->getPost('action', false);
        switch ($action){
          case 'start':
            $model->logme = 1;
            $model->save(false);
            break;
          case 'stop':
            $model->logme = 0;
            $model->save(false);
            break;
          default:

          $criteria = new CDbCriteria();
          $criteria->order = "date DESC";
          $criteria->addCondition("user_id = :user_id");
          $criteria->params[':user_id'] = $model->id;
          $criteria->limit = 100;
          $botData = Antibot::model()->findAll($criteria);
          foreach ($botData as $one) {
            /** @var $one Antibot */
            ?>
            <tr class="data">
              <td><?= $one->date ?></td>
              <td><?= $one->page ?></td>
              <td><?= $one->hp ?></td>
              <td><?= $one->energy ?></td>
              <td><?= $one->x ?></td>
              <td><?= $one->y ?></td>
              <td><?= $one->ip ?></td>
            </tr>
            <?php
          }
        }
      }
    }
  }
  public function actionGift($id){
    $model = $this->loadModel($id);
    $this->renderPartial('gift', array('model' => $model));
  }

  public function actionRemove($id){
    $model = $this->loadModel($id);
    $this->renderPartial('remove', array('model' => $model));
  }

  public function actionAutocompleteItem($term) {
    /*
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
    */
	  $res = [];
	  $limit = 10;

	  $query = "
					SELECT foo.id, tr.value, foo.type FROM(
					  SELECT *, CONCAT('@@@equipment@@name@@id@@', t.id) slug FROM `equipment` `t` 
					  WHERE clan=0
					) as foo
					LEFT JOIN (
					  SELECT * FROM rev_language_translate WHERE slug LIKE '@@@equipment@@name@@id@@%' AND lang_id IN (1, 2)
					) tr ON tr.slug = foo.slug
					WHERE (tr.value LIKE '%$term%' OR foo.id = '$term') 
					ORDER BY tr.value
					LIMIT $limit";
	  $models = Yii::app()->db->createCommand($query)->queryAll();
	  foreach ($models as $one){
		  $label = "{$one['value']} [{$one['id']}]";
		  $res[$label] = [
			  'id'    => $one['id'],
			  'value' => $one['value'],
			  'label' => $label
		  ];
	  }
    sort($res);

    echo json_encode($res);
    Yii::app()->end();
  }

  public function actionGifts() {
      $this->pageTitle .= ' - выдача предметов';

      $model = new GiftsForm;

      if (isset($_POST['GiftsForm'])) {
          //Отправка
          $model->attributes = $_POST['GiftsForm'];
          // validate user input and redirect to the previous page if valid
          if ($model->validate()) {
              $this->render('gifts-send', array(
                  'model' => $model,
              ));
              return;
          } else {
              Yii::app()->user->setFlash(TbHtml::ALERT_COLOR_DANGER, 'Неправильные параметры выдачи');
          }
      }

      $this->render('gifts', array(
          'model' => $model,
      ));
  }

  public function actionGiftsSend() {
      $info  = Yii::app()->request->getPost('info', array());
      $model = new GiftsForm();
      $model->attributes = $info;

      //$ids   = Yii::app()->request->getPost('ids', array());
      $ids = $model->getIds();
      $kol_players = count($ids);
      $pcount = 0;

      $items = [];
      foreach($model->item as $i => $item) {
          if ($item != 0) {
              $count = isset($model->count[$i]) ? $model->count[$i] : 0;
              $items[$item] = $count;
          }
      }

      if ($kol_players>0 && (count($items)>0 || $model->napad>0 || $model->pohod>0 || $model->pleft>0)) {

          $sql_items = serialize($items);
          /*
          $gift = new Gifts();
          $gift->player_id = $playerId;
          $gift->items     = serialize($items);
          $gift->napad     = $model->napad;
          $gift->pohod     = $model->pohod;
          $gift->pleft     = $model->pleft;
          $gift->text      = $model->text;
          $gift->text_en   = $model->text_en;
          $gift->save();
          */

          $select_sql = "SELECT 
                            id as player_id,
                            '{$sql_items}' as items,
                            '{$model->napad}' as napad,
                            '{$model->pohod}' as pohod,
                            '{$model->pleft}' as pleft,
                            '{$model->text}' as text,
                            '{$model->text_en}' as text_en 
                          FROM players";

          switch($model->type) {
              case $model::SEND_TYPE_ALL:
                  break;
              case $model::SEND_TYPE_BY_ID:
                  $select_sql .= " WHERE id BETWEEN '{$model->id_from}' AND '{$model->id_to}'";
                  break;
              case $model::SEND_TYPE_LOGIN:
                  $date_start = strtotime($model->start);
                  $date_end = strtotime($model->end);
                  $select_sql .= " WHERE lpv BETWEEN '{$date_start}' AND '{$date_end}'";
                  break;
              case $model::SEND_TYPE_LEVEL:
                  $select_sql .= " WHERE level BETWEEN '{$model->level_from}' AND '{$model->level_to}'";
                  break;
          }


          $sql = "INSERT INTO rev_gifts (player_id, items, napad, pohod, pleft, text, text_en) ({$select_sql});";
          $pcount = Yii::app()->db->createCommand($sql)->execute();
      }

      echo $pcount;
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Players the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model = Players::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Players $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'players-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
