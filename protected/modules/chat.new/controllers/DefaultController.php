<?php

class DefaultController extends ModuleController {
	public $pageTitle = 'Чат';

	public function actionIndex() {
		Yii::app()->theme = 'main';

		if (!Yii::app()->stat->id) $this->redirect('/');

		$chatPlayer = ChatPlayer::model()->findByPk( Yii::app()->stat->id );
		// Если записи в базе не существует, то добавляем её
		if ( ! $chatPlayer ) {
			$chatPlayer            = new ChatPlayer();
			$chatPlayer->player_id = Yii::app()->stat->id;
		}
		$last_activity = $chatPlayer->last_activity;

		$chatPlayer->last_activity = new CDbExpression( 'NOW()' );
		$chatPlayer->lang_slug     = Yii::app()->stat->lang_slug;
		$chatPlayer->save();

		$onlineReal = ChatPlayer::getUsers(Yii::app()->stat->lang_slug, false, false);
		$online = ChatPlayer::getUsers(Yii::app()->stat->lang_slug);
		$lang   = Yii::app()->stat->lang_slug;

		$players = $this->renderPartial('__playerList', array('players' => $online), true);
		foreach ($online as $one) { Players::SendCMD( 'chat_'.$one['id'], 'chat_load_users_list', array( 'list' => $players, 'lang' => $lang )); }
		if ( !isset($onlineReal[Yii::app()->stat->id]) && ((time() - strtotime( $last_activity ) ) > 2 * 60)) {
			$message = $this->renderPartial('__message', array(
				'message' => array(
					'id' => 0,
					'time' => date( 'H:i' ),
					'user_id' => Yii::app()->stat->id,
					'user'    => Yii::app()->stat->user,
					'style'   => Extra::getChatCssColorUser(Yii::app()->stat->rank),
					'private' => false,
					'message' => '<span class="colorSystem">' . t::get( 'Игрок вошел в чат' ) . '</span>'
			)), true);
			foreach ($online as $one) {
				if ($one['id'] != Yii::app()->stat->id) {
					Players::SendCMD( 'chat_'.$one['id'], 'chat_add_text', array('text' => $message, 'lang' => $lang));
				}
			}
		}

		$chatModel = new RChat();
		$this->render( 'index', array(
			'model'    => $chatModel,
			'players'  => $players,
			'messages' => RChat::getChats()
		) );

	}

}