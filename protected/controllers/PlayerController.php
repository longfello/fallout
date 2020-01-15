<?php

class PlayerController extends GameController {
	public $layout = 'base';

	public function actionAppearance() {
		$this->pageTitle  = t::get('Внешний вид персонажа');
		Yii::app()->getModule('chat');
		Yii::app()->clientScript->registerCssFile('/css/components/set-appearance.css');
		Yii::app()->clientScript->registerScriptFile('/js/components/set-appearance/index.js');

		$races   = PlayerRace::model()->findAll();
		$layouts = AppearanceLayout::model()->findAll(['order' => 'sort_order']);

// Сохранение аватара для чата
		if (Yii::app()->request->isPostRequest && $avatar_id = Yii::app()->request->getPost('chat_avatar_pic', false)) {
			$model = ChatAvatarBase::model()->findByPk($avatar_id);
			if ($model){
				// Если аватар донатовый, проверим, покупали ли его игрок
				if ($model->owner > 0 && $model->owner != Yii::app()->stat->id) {
					Yii::app()->user->setFlash('error', t::get('Этот аватар принадлежит не Вам'));
				} else {
					$avatar = ChatAvatar::model()->findByPk(Yii::app()->stat->id);
					if (!$avatar){
						$avatar = new ChatAvatar();
						$avatar->player_id = Yii::app()->stat->id;
					}
					$avatar->avatar_id = $avatar_id;
					$avatar->save();
				}
			}
			$this->redirect('/player/appearance');
		}

		require_once (basedir.'/game_tools.php');

		$this->render( 'appearance', [
			'chatAvatar' => ChatAvatar::model()->findByAttributes(['player_id' => Yii::app()->stat->id]),
			'player'     => Yii::app()->stat->model,
			'races'      => $races,
			'layouts'    => $layouts,
			'appearance' => Appearance::model()->findByPk(Yii::app()->stat->id)
		]);
	}

	public function actionSaveLayout(){
		if (Yii::app()->request->isPostRequest) {
			$layouts = Yii::app()->request->getParam('layout', []);
			if (is_array($layouts)){
     			$player     = Yii::app()->stat->model;
				$appearance = Appearance::model()->findByPk($player->id);
				if ((!$appearance->free_set_used) || ($player->platinum >= 1000)) {

					PlayerAppearance::model()->deleteAllByAttributes([
						'player_id' => $player->id
					]);

					foreach ( $layouts as $key => $value ) {
						switch ( $key ) {
							case 'race':
								$race = PlayerRace::model()->findByPk( $value );
								if ( $race ) {
									$player->race_id = $race->id;
									$player->save( false );
								}
								break;
							case 'gender':
								if ( in_array( $value, [ Players::GENDER_MALE, Players::GENDER_FEMALE ] ) ) {
									$player->gender = $value;
									$player->save( false );
								}
								break;
							default:
								$layout     = AppearanceLayout::model()->findByPk( $key );
								$valueModel = PlayerRaceAppearanceList::model()->findByPk( $value );
								if ( $layout && $valueModel && $valueModel->appearance_layout_id == $layout->id ) {
									$model                       = new PlayerAppearance();
									$model->player_id            = $player->id;
									$model->appearance_layout_id = $layout->id;
									$model->player_race_appearance_id = $valueModel->id;
									$model->save();
								}
						}
					}

					if ($appearance->free_set_used) {
						$player->platinum -= 1000;
						$player->save(false);
					}
					$appearance->free_set_used = 1;
					$appearance->free_race_change_until = 0;
					$appearance->save(false);

				} else {
					Yii::app()->user->setFlash('error', t::get('К сожалению, у вас недостаточно крышек на счету'));
				}
			}
		}
		$this->redirect('/player/appearance');
	}

	public function actionLayout(){
		$layout_id = Yii::app()->request->getParam('layout', 0);
		$race_id = Yii::app()->request->getParam('race', 0);
		$gender_id = Yii::app()->request->getParam('gender', 0);
		$layouts = PlayerRaceAppearanceList::model()->findAllByAttributes([
			'race_id' => $race_id,
			'gender'  => $gender_id,
			'appearance_layout_id' => $layout_id,
		]);
		if ($layouts) {
			foreach ($layouts as $layout){
				echo("<li data-id='{$layout->id}' data-url='".$layout->getPicture('layout', true)."'></li>");
			}
		}
	}
	
	public function actionInvite($provider = false) {
		$this->pageTitle = t::get('Пригласить друга');
		Yii::app()->clientScript->registerScriptFile('/js/components/clipboard.min.js');
		Yii::app()->clientScript->registerScriptFile('/js/components/player_invite.js');
        Yii::app()->clientScript->registerScriptFile('/js/components/copy_referal.js');

		$user_contacts = [];
		if ($provider) {
			if ($provider=='live') {
				require_once(Yii::getPathOfAlias('application.components') . "/msapi/OAuth.php");
				require_once(Yii::getPathOfAlias('application.components') . "/msapi/Outlook.php");

				if (!Microsoft\MsAPI\OAuth::loggedIn()) {
					if (!isset($_GET['code'])) {
						$url = Microsoft\MsAPI\OAuth::getLoginUrl(Microsoft\MsAPI\OAuth::getRedirectUri('/player/invite/live'));
						$this->redirect($url);
					} else {
						$auth_code = $_GET['code'];

						$tokens = Microsoft\MsAPI\OAuth::getTokenFromAuthCode($auth_code, Microsoft\MsAPI\OAuth::getRedirectUri('/player/invite/live'));

						if (isset($tokens['access_token'])) {
							$_SESSION['msapi_access_token'] = $tokens['access_token'];
							$_SESSION['msapi_refresh_token'] = $tokens['refresh_token'];

							$expiration = time() + $tokens['expires_in'] - 300;
							$_SESSION['msapi_token_expires'] = $expiration;

							$user = Microsoft\MsAPI\Outlook::getUser($tokens['access_token']);
							$_SESSION['msapi_user_email'] = $user['EmailAddress'];
						}
						$this->refresh();
					}
				} else {
					$contacts = Microsoft\MsAPI\Outlook::getContacts($_SESSION['msapi_access_token'], $_SESSION['msapi_user_email']);
					if (isset($contacts['value'])) {
						foreach($contacts['value'] as $contact) {
							foreach ($contact['EmailAddresses'] as $email_address) {
								$user_contacts[] = (object)[
									'email'=>$email_address['Address'],
									'displayName'=>$contact['GivenName']." ".$contact['Surname']
								];
							}
						}
					}
					if (count($user_contacts)==0) {
						Yii::app()->user->setFlash('error', t::get( "Не удалось получить контакты или их нет." ));
					}
				}
			} else {
				$config_file_path = Yii::getPathOfAlias('application.components').'/hybridauth/hybridauth/config.php';
				require_once(Yii::getPathOfAlias('application.components') . "/hybridauth/hybridauth/Hybrid/Auth.php" );
				require_once(Yii::getPathOfAlias('application.components') . "/hybridauth/hybridauth/Hybrid/Endpoint.php" );
				try
				{
					$hybridauth = new Hybrid_Auth( $config_file_path );
					Hybrid_Auth::$config["base_url"] = "https://".BASE_DOMAIN."/player/invite/".$provider;
					if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done']))
					{
						Hybrid_Endpoint::process();
					}
					$provider = $hybridauth->authenticate($provider);
					$user_contacts = $provider->getUserContacts();
					if (count($user_contacts)==0) {
						Yii::app()->user->setFlash('error', t::get( "Не удалось получить контакты или их нет." ));
					} else {
                        usort($user_contacts, function($a, $b)
                        {
                            return strcmp($a->email, $b->email);
                        });
                    }
				} catch( Exception $e ){
					$message = false;
					switch( $e->getCode()) {
						case 0 :
							$message = t::get( "Unspecified error." );
							break;
						case 1 :
							$message = t::get( "Configuration error." );
							break;
						case 2 :
							$message = t::get( "Provider not properly configured." );
							break;
						case 3 :
							$message = t::get( "Unknown or disabled provider." );
							break;
						case 4 :
							$message = t::get( "Missing provider application credentials." );
							break;
						case 5 :
							$message = t::get( "Authentification failed. The user has canceled the authentication or the provider refused the connection." );
							break;
						case 6 :
							$message = t::get( "User profile request failed. Most likely the user is not connected to the provider and he should authenticate again." );
							break;
						case 7 :
							$message = t::get( "User not connected to the provider." );
							break;
						case 8 :
							$message = t::get( "Provider does not support this feature." );
							break;
						default:
							$message = t::get( "Unspecified error." );
							break;
					}
					if ($message) Yii::app()->user->setFlash('error', $message);
				}
			}
		} else {
			unset($_SESSION['msapi_access_token']);
			unset($_SESSION['msapi_refresh_token']);
			unset($_SESSION['msapi_token_expires']);
			unset($_SESSION['msapi_user_email']);
		}

		for ($i=0; $i<count($user_contacts); $i++) {
			$email = $user_contacts[$i]->email;

			$checkEmail = _mysql_num_rows(_mysql_exec("select * from players where email='$email' limit 1"));
            $checkInvite = _mysql_num_rows(_mysql_exec("select * from rev_invite_log where (`email`='$email' AND `owner`='".Yii::app()->stat->id."' AND created_at > DATE_SUB(CURDATE(),INTERVAL 1 MONTH)) OR (`email`='$email' AND unsubscribed='1') limit 1"));

			if ($checkEmail>0 || $checkInvite>0) {
				unset($user_contacts[$i]);
			}
		}

        $invite_bonus_level_tmp =  Config::model()->findByAttributes(['config_name'=>'invite_bonus_level']);
        $invite_bonus_levels = explode(',',$invite_bonus_level_tmp->config_value);

        $criteria=new CDbCriteria();
        $criteria->addCondition('owner = :user_id');
        $criteria->params = array(':user_id' => Yii::app()->stat->id);
        $criteria->order = 'created_at DESC';
        $count = InviteLog::model()->count($criteria);
        $pages = new CPagination($count);

        // results per page
        $pages->pageSize=100;
        $pages->applyLimit($criteria);
        $invites = InviteLog::model()->findAll($criteria);
        $player = Players::model()->findByPk(Yii::app()->stat->id);

		$this->render('invite',[
			'invites' => $invites,
            'pages' => $pages,
			'user_contacts' => $user_contacts,
			'share_url' => "https://".$_SERVER['SERVER_NAME']."/register.php?ref=".Yii::app()->stat->id,
			'caps_send' =>Config::model()->findByAttributes(['config_name'=>'invite_send_bonus_mail']),
			'caps_reg' =>Config::model()->findByAttributes(['config_name'=>'invite_reg_bonus']),
            'invite_bonus_levels_count' => count($invite_bonus_levels),
            'player' => $player
		]);
	}

	public function actionReferals() {
        $this->pageTitle = t::get('Список рефералов');
        Yii::app()->clientScript->registerScriptFile('/js/components/clipboard.min.js');
        Yii::app()->clientScript->registerScriptFile('/js/components/copy_referal.js');

        $referals = Players::model()->findAllByAttributes(array('ref' => Yii::app()->stat->id), array('order'=>'reg_date DESC'));

        $this->render('referals',[
            'referals'=>$referals,
            'share_url' => "https://".$_SERVER['SERVER_NAME']."/register.php?ref=".Yii::app()->stat->id,
        ]);
    }

	public function actionInviteGroup()
	{
		$emails = Yii::app()->request->getPost('emails');

		$ret = array();
		$ret['errors'] = '';
		$ret['result'] = 0;

		require_once(basedir . '/inc/class.phpmailer.php');

		$user_href = "https://".$_SERVER['SERVER_NAME'].'/register.php?ref=' . Yii::app()->stat->id;
		$subject = t::get("player_invite_mail_subject");

		$mail = new PHPMailer();
		$mail->CharSet = 'utf-8';
		$mail->SetFrom('support@revival.online', 'revival.online');
		$mail->AddReplyTo('support@revival.online', 'revival.online');

		$mail->Subject = $subject;

		$sended = 0;
		$sended_caps = 0;
		$caps_send = Config::model()->findByAttributes(['config_name'=>'invite_send_bonus_mail']);
		$caps_send_value = (int)$caps_send->config_value;
		foreach ($emails as $email) {
            $body =  t::get("player_invite_mail_body", array(Yii::app()->stat->user, Yii::app()->stat->id, $user_href, $user_href, $email));
            $mail->MsgHTML($body);

			$checkEmail = _mysql_num_rows(_mysql_exec("select * from players where email='$email' limit 1"));
			$checkInvite = _mysql_num_rows(_mysql_exec("select * from rev_invite_log where (`email`='$email' AND `owner`='".Yii::app()->stat->id."' AND created_at > DATE_SUB(CURDATE(),INTERVAL 1 MONTH)) OR (`email`='$email' AND unsubscribed='1') limit 1"));

			if ((preg_match("/^([a-zA-Z0-9_]+[a-zA-Z0-9_\.\+-])*@[A-Za-z0-9_]+\.([A-Za-z_\.]*)$/", $email)) && $checkEmail == 0 && $checkInvite==0) {
				$mail->ClearAddresses();
				$mail->AddAddress($email);

				if ($mail->Send()) {
					$sended++;

					$ilog = InviteLog::model()->findByAttributes([
						'email'    => $email,
						'owner'    => Yii::app()->stat->id
					]);

					if (!$ilog) {
						$ilog = new InviteLog();
						$ilog->owner = Yii::app()->stat->id;
						$ilog->email = $email;

                        $max_count =  Config::model()->findByAttributes(['config_name'=>'invite_max_send']);
                        $max_count_value = (int)$max_count->config_value;

                        $invite_count_sql = "SELECT COUNT(*) FROM rev_invite_log WHERE `owner`='".Yii::app()->stat->id."' AND created_at > DATE_SUB(CURDATE(),INTERVAL 1 MONTH)";
                        $invite_count = Yii::app()->db->createCommand($invite_count_sql)->queryScalar();

                        if ($invite_count<$max_count_value) {
                            $ilog->caps_send  = $caps_send_value;
                            $sended_caps++;
                        } else {
                            $ilog->caps_send  = 0;
                        }
					}

					$ilog->created_at = new CDbExpression('NOW()');
					$ilog->save();
				}
			}
		}

		if($sended_caps>0) {
			$player = Players::model()->findByPk(Yii::app()->stat->id);
			if ($player) {
				$player->platinum += $sended_caps*$caps_send_value;
				$player->save(false);
				logdata_invite_group_platinum::add(Yii::app()->stat->id, $sended_caps*$caps_send_value, $sended);
			}
		}


		$ret['result'] = 1;
		Yii::app()->user->setFlash('success', t::get('%s из %s приглашений были успешно отправлены.', array($sended, count($emails),)));

		echo(CJavaScript::jsonEncode($ret));
		Yii::app()->end();
	}

	public function actionActivation(){
		if (Yii::app()->stat->model->email_confirmed == Players::YES) {
			$this->redirect('/');
		}
		$code = Yii::app()->request->getPost('code', '');

		Yii::app()->stat->model->email_confirmed = (($code == Yii::app()->stat->model->pass) && Yii::app()->stat->model->pass)?Players::YES:Players::NO;
		Yii::app()->stat->model->save(false);

		$this->render('activation', [
			'model' => Yii::app()->stat->model
		]);
	}
	public function actionSendActivation(){
		require_once(basedir.'/inc/class.phpmailer.php');

		$error = false;
		$email = Yii::app()->request->getPost('email', Yii::app()->stat->model->email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = t::get('Введен неверный адрес электронной почты');
		}

		if (!$error){
			$player = Players::model()->findBySql("SELECT * FROM players WHERE email='{$email}' AND id <> ". Yii::app()->stat->id." AND email_confirmed='". Players::YES ."'");
			if ($player){
				$error = t::get('Введеный email принадлежит другому игроку.');
			}
		}

		if (!$error) {
			Yii::app()->stat->model->email = $email;
			Yii::app()->stat->model->email_confirmed = Players::NO;
			Yii::app()->stat->model->pass = Yii::app()->getSecurityManager()->generateRandomString(8,true);
			Yii::app()->stat->model->save(false);

			$mail = new PHPMailer();
			$body = $this->renderPartial('application.views.email.activation', ['model' => Yii::app()->stat->model], true);
			$mail->CharSet       = 'utf-8';
			$mail->SetFrom('support@revival.online', 'revival.online');
			$mail->AddReplyTo('support@revival.online', 'revival.online');
			$mail->Subject       =  t::get('Код активации аккаунта %s', [Yii::app()->stat->model->user]);
			$mail->MsgHTML($body);
			$mail->AddAddress(Yii::app()->stat->model->email, Yii::app()->stat->model->user);
			$mail->Send();
		}

		$this->render('send-activation', ['error' => $error, 'email' => $email]);
	}
}