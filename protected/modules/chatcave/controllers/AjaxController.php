<?php

class AjaxController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + SubmitForm, GetChats + ignore + CheckIgnore + GetIgnorePlayers + DeleteIgnore + GetCurrentTime + DeleteMessage',
        );
    }


    public function actionSubmitForm()
    {
        $chat = new RChat();
        $chat->attributes = $_POST['RChat'];
        $chat->lang_slug = Yii::app()->stat->lang_slug;
        $chat->save();

        echo CJSON::encode(array('insertId' => $chat->id));
    }


    /**
     * Получить сообщения чата
     */
    public function actionGetChats()
    {
        $lastId = Yii::app()->request->getQuery('lastId');
        $chats = array();
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('c.id, c.message, DATE_FORMAT(c.dt, "%H:%i") AS time, c.to_player, p.user AS user, p.rank, p.id AS user_id, pp.user AS private_user');
        $cmd->from('{{chat_cave}} c');
        $cmd->join('players AS p', 'p.id = c.player_id');
        $cmd->leftJoin('players AS pp', 'pp.id = c.to_player');
        $cmd->order('c.id DESC');
        $cmd->andWhere('c.id > :id', array(':id' => $lastId));
        $cmd->andWhere("c.lang_slug = :lang_slug", array(':lang_slug' => Yii::app()->stat->lang_slug));
	    $uid =  (Yii::app()->stat && Yii::app()->stat->id) ? Yii::app()->stat->id: 0;
	    $uid = (int)$uid;

        if (Yii::app()->stat->rank != 'Админ')
        {
          $cmd->andWhere('c.to_player = 0 OR c.to_player = ' . $uid . ' OR (c.player_id = ' . $uid . ' AND c.to_player > 0)');
        }
        $cmd->limit(30);
        $chatsReader = $cmd->query();

        while($row = $chatsReader->read())
        {
            if ($row['user_id'] == $uid)
                $row['style'] = 'colorCurrent';
            else
                $row['style'] = Extra::getChatCssColorUser($row['rank']);

            $row['message'] = Extra::forMe($row['message']);

            array_unshift($chats, $row);
        }

      $ret = array('chats' => $chats);

      $cavesLocations = array('/labyrinth.php', '/caves.php');
      if (!in_array(Yii::app()->stat->travel_place, $cavesLocations)){
        $ret['redirect'] = '/city.php';
      }

      echo CJSON::encode($ret);
    }


    /**
     * Получить пользователей чата
     */
    public function actionGetUsers()
    {
        // Обновляем время последнего посещения у текущего игрока
        ChatPlayer::model()->updateByPk(Yii::app()->stat->id, array('last_activity' => new CDbExpression('NOW()'),'lang_slug' =>Yii::app()->stat->lang_slug));

        // Получить всех активных в чате игроков в течении последних 30 секунд
        $users = ChatPlayer::getUsers(Yii::app()->stat->lang_slug);

        // Получить статус для текущего игрока: сделать рефреш сайта или нет
        $refresh = ChatPlayer::model()->findByPk(Yii::app()->stat->id);
        $refreshStatus = $refresh->update_chat;
        $refresh->update_chat = 0;
        $refresh->save(false);

        echo CJSON::encode(array('users' => $users, 'total' => count($users), 'refresh' => $refreshStatus));
    }


    /**
     * Получить данные текущего игрока
     */
    public function actionGetCurrentUser()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, user, rank, clan, travel_place';
	    $criteria->condition = 'id=' . (int)Yii::app()->stat->id;
        $player = Players::model()->find($criteria)->attributes;

        $player['chatban'] = ChatPlayer::isBan(Ban::getBanTime(Ban::BAN_CHAT, $player));
        $player['travel_place'] = ChatPlayer::isCave($player['travel_place']);

        // Обновить текущее время онлайн
        Players::model()->updateByPk(Yii::app()->stat->id, array('lpv' => time()));

        echo CJSON::encode($player);
    }


    /**
     * Добавить или убрать игрока из игнора
     */
    public function actionIgnore()
    {
        $add = Yii::app()->request->getPost('add');
        $playerId = Yii::app()->request->getPost('playerId');

        if ($add)
        {
            $chatIgnore = new ChatIgnore();
            $chatIgnore->player_id = Yii::app()->stat->id;
            $chatIgnore->ignore_player_id = $playerId;
            $chatIgnore->save(false);
        }
        else
        {
            /** @var ChatIgnore $chatIgnore */
            ChatIgnore::model()->deleteByPk(array('player_id' => Yii::app()->stat->id, 'ignore_player_id' => $playerId));
        }
    }


    /**
     * Проверить: находиться ли игрок в игноре
     */
    public function actionCheckIgnore()
    {
        $playerId = Yii::app()->request->getPost('playerId');

        $chatIgnore = ChatIgnore::model()->findByPk(array('player_id' => Yii::app()->stat->id, 'ignore_player_id' => $playerId));
        $myIgnore =  $chatIgnore ? 1 : 0;

        $chatIgnore = ChatIgnore::model()->findByPk(array('player_id' => $playerId, 'ignore_player_id' => Yii::app()->stat->id));
        $userIgnore = $chatIgnore ? 1 : 0;

        echo CJSON::encode(array('myIgnore' => $myIgnore, 'userIgnore' => $userIgnore));
    }


    /**
     * Получить игроков в игноре
     */
    public function actionGetIgnorePlayers()
    {
        $ignorePlayers = ChatIgnore::getIgnorePlayers();

        echo CJSON::encode(array('ignorePlayers' => $ignorePlayers));
    }


    /**
     * Удалить игрора из игрока из игнор-листа
     */
    public function actionDeleteIgnore()
    {
        $playerId = Yii::app()->request->getPost('playerId');
        ChatIgnore::model()->deleteByPk(array('player_id' => Yii::app()->stat->id, 'ignore_player_id' => $playerId));
    }


    /**
     * Получить текущее время
     */
    public function actionGetCurrentTime()
    {
        $time = Yii::app()->db->createCommand()
            ->select('DATE_FORMAT(NOW(), "%H:%i")')
            ->from('{{achieve_to_player}}')
            ->queryScalar();

        echo CJSON::encode(array('time' => $time));
    }


    /**
     * Удалить сообщение с базы
     */
    public function actionDeleteMessage()
    {
        $chatId =  Yii::app()->request->getPost('chat-id');

        RChat::model()->deleteByPk($chatId);

        // Выставить параметр для полной перезагрузки чата для всех игрков онлайн
        ChatPlayer::model()->updateAll(array('update_chat' => 1), 'player_id NOT IN (' . Yii::app()->stat->id . ')');
    }

}