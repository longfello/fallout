<?php

class AjaxController extends Controller
{
    public function filters()
    {
        return array(
            'ajaxOnly + SubmitForm, GetChats + ignore + CheckIgnore + GetIgnorePlayers + DeleteIgnore + GetCurrentTime + DeleteMessage + Unload'
        );
    }

    public function beforeAction($action){
	    // Обновляем время последнего посещения у текущего игрока
	    ChatPlayer::model()->updateByPk(Yii::app()->stat->id, array('last_activity' => new CDbExpression('NOW()'),'lang_slug' =>Yii::app()->stat->lang_slug));
	    return parent::beforeAction($action);
    }

    public function actionSubmitForm()
    {
    	$lang = Yii::app()->stat->lang_slug;
        $chat = new RChat();
	    $chat->attributes = $_POST['RChat'];
        $chat->lang_slug = $lang;

	    if ($chat->to_player > 0) {
	    	// Приват. Проверить игноры.
		    $chatIgnore = ChatIgnore::model()->findByPk(array('player_id' => Yii::app()->stat->id, 'ignore_player_id' => $chat->to_player));
		    if ($chatIgnore) {
			    $message = $this->renderPartial('/default/__message', array(
				    'message' => array(
					    'id' => 0,
					    'time' => date( 'H:i' ),
					    'user_id' => Yii::app()->stat->id,
					    'user'    => Yii::app()->stat->user,
					    'style'   => Extra::getChatCssColorUser(Yii::app()->stat->rank),
					    'private' => false,
					    'message' => '<span class="colorSystem">' . t::get( 'Данный игрок добавлен вами в игнор' ) . '</span>'
				    )), true);
			    Players::SendCMD( 'chat_'.Yii::app()->stat->id, 'chat_add_text', array('text' => $message, 'lang' => $lang));
			    return;
		    }
		    $chatIgnore = ChatIgnore::model()->findByPk(array('player_id' => $chat->to_player, 'ignore_player_id' => Yii::app()->stat->id));
		    if ($chatIgnore) {
			    $message = $this->renderPartial('/default/__message', array(
				    'message' => array(
					    'id' => 0,
					    'time' => date( 'H:i' ),
					    'user_id' => Yii::app()->stat->id,
					    'user'    => Yii::app()->stat->user,
					    'style'   => Extra::getChatCssColorUser(Yii::app()->stat->rank),
					    'private' => false,
					    'message' => '<span class="colorSystem">' . t::get( 'Данный игрок добавил вас в игнор' ) . '</span>'
				    )), true);
			    Players::SendCMD( 'chat_'.Yii::app()->stat->id, 'chat_add_text', array('text' => $message, 'lang' => $lang));
			    return;
		    }
	    }
	    $chat->save();
	    $online = ChatPlayer::getUsers(Yii::app()->stat->lang_slug);
	    $lang   = Yii::app()->stat->lang_slug;
	    $players = $this->renderPartial('/default/__playerList', array('players' => $online), true);

	    Players::SendCMD('chat', 'chat_reload_chat', array(
	    	'lang' => $lang,
		    'list' => $players
	    ));

        echo CJSON::encode(array('insertId' => $chat->id));
    }


    /**
     * Получить сообщения чата
     */
    public function actionGetChats()
    {
        $lastId = Yii::app()->request->getQuery('lastId');
	    $messages = RChat::getChats($lastId);
	    $ret = array(); $last_id = 0;

	    foreach($messages as $message) {
		    $last_id = max($message['id'], $last_id);
		    array_unshift($ret, $this->renderPartial('/default/__message', array('message' => $message), true));
	    }

        echo CJSON::encode(array(
        	'messages' => $ret,
	        'last_id' => $last_id,
        ));
    }


    /**
     * Получить пользователей чата
     */
    public function actionGetUsers()
    {
        // Получить всех активных в чате игроков в течении последних 30 секунд
        $users = ChatPlayer::getUsers(Yii::app()->stat->lang_slug);

        // Получить статус для текущего игрока: сделать рефреш сайта или нет
        $refresh = ChatPlayer::model()->findByPk(Yii::app()->stat->id);
        $refreshStatus = $refresh->update_chat;
        $refresh->update_chat = 0;
        $refresh->save(false);

        echo CJSON::encode(array('users' => $users, 'total' => count($users), 'refresh' => $refreshStatus));
    }

    public function actionUnload(){
    	/*
	    $online = ChatPlayer::getUsers(Yii::app()->stat->lang_slug, Yii::app()->stat->id);
	    $lang   = Yii::app()->stat->lang_slug;

	    $players = $this->renderPartial('/default/__playerList', array('players' => $online), true);
	    foreach ($online as $one) { Players::SendCMD( 'chat_'.$one['id'], 'chat_load_users_list', array( 'list' => $players, 'lang' => $lang )); }
    	*/
	    echo('Unloaded');
    }


    /**
     * Получить данные текущего игрока
     */
    public function actionGetCurrentUser()
    {
    	if (Yii::app()->stat && Yii::app()->stat->id) {
		    $criteria = new CDbCriteria();
		    $criteria->select = 'id, user, rank, clan, level';
		    $criteria->condition = 'id=' . Yii::app()->stat->id;
		    $player = Players::model()->find($criteria)->attributes;

		    if ($player) {
			    $player['chatban'] = ChatPlayer::isBan(Ban::getBanTime(Ban::BAN_CHAT, $player));
			    // Обновить текущее время онлайн
			    Players::model()->updateByPk(Yii::app()->stat->id, array('lpv' => time()));
			    echo CJSON::encode($player);
		    }
	    }
    }


    /**
     * Добавить или убрать игрока из игнора
     */
    public function actionIgnore()
    {
        $add = Yii::app()->request->getPost('add');
        $playerId = Yii::app()->request->getPost('playerId');

        if ($add) {
            $chatIgnore = new ChatIgnore();
            $chatIgnore->player_id = Yii::app()->stat->id;
            $chatIgnore->ignore_player_id = $playerId;
            $chatIgnore->save(false);
        } else {
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

	    Players::SendCMD('chat', 'chat_reload_chat', array(
		    'lang' => Yii::app()->stat->lang_slug,
		    'force' => 1
	    ));
    }

}