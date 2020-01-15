<?php

class SiteController extends Controller
{
  public $layout          = 'home';
	public $langModel       = t::MODEL_HOME;
  public $metaDescription = '';
  public $loginForm       = false;
	public $bodyClass       = 'home';

	public function init(){
    $this->pageTitle  = Yii::app()->name;
		Yii::app()->theme = 'public';

//	echo(Yii::getPathOfAlias('webroot')); die();
    require_once(Yii::getPathOfAlias('webroot').'/config.php');
    require_once(Yii::getPathOfAlias('webroot').'/classes/ez_sql.php');
    require_once(Yii::getPathOfAlias('webroot').'/classes/index/Login_Handler.php');
    require_once(Yii::getPathOfAlias('webroot').'/classes/index/Registration_Handler.php');
    require_once(Yii::getPathOfAlias('webroot').'/classes/index/Recovery_Handler.php');
	}

	/*
	public function filters()
	{
		return array(
			array(
				'COutputCache + index + tor',
				'duration'=> 30*60, // 30 минут
				'varyByExpression' => '(int)Yii::app()->user->isGuest . t::iso()',
			),
		);
	}
	*/

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	public function actionIndex(){
        if (preg_match('|index.php|',$_SERVER['REQUEST_URI'])){
            $this->redirect('/');
        }

		$code = Yii::app()->request->getParam('activate', false);

        if ($code) {
        	$player = Players::model()->findByAttributes(['pass' => $code, 'email_confirmed' => Players::NO]);
        	if ($player) {
		        $player->email_confirmed = (($code == $player->pass) && $player->pass)?Players::YES:Players::NO;
		        $player->save(false);

		        if ($player->email_confirmed) {
			        $_SESSION['userid'] = $player->id;
			        $_SESSION['user'] = $player->user;
			        $_SESSION['pass'] = $player->pass2;
			        $player->popupInvite();
			        Popup::add($player->id, t::get('Ваш адрес электронной почты успешно подтвержден!'));
			        $this->redirect('/city.php');
		        }
	        }
	        $this->redirect('/');
        }

		$this->loginForm = true;
        Yii::app()->getModule('thumbler');

        $title_domain = preg_replace('/https?:\/\/|www./', '', Yii::app()->getBaseUrl(true));
        $this->pageTitle = t::get('metatag_title_home')." ".$title_domain;
        $this->metaDescription = t::get('metatag_desc_home');
        //$sql='SELECT * FROM `clans` ORDER BY union_win LIMIT 7';

        $sql='SELECT
                `c`.*,
                (SELECT
                    IFNULL(SUM(`point`), 0)
                FROM
                    `clan_war_history`
                WHERE `clan_id` = `c`.`id`
                    AND MONTH(`date`) = MONTH(NOW())
                    AND YEAR(`date`) = YEAR(NOW())) AS `month_points`
            FROM
                `clans` AS `c`
            ORDER BY `month_points` DESC LIMIT 7';
        $clans=Clans::model()->cache(3600)->findAllBySql($sql);
        foreach ($clans as $one) {
          $one['name'] = t::getDb('name','clans','id', $one['id']);
        }


        $sql="SELECT * FROM `players` WHERE rank='Игрок' AND level < 1000 ORDER BY level DESC, exp DESC LIMIT 7";
        $players=Players::model()->cache(3600)->findAllBySql($sql);

        $criteria = new CDbCriteria();
        $criteria->order = "date DESC";
        $criteria->limit = 5;
        $criteria->addCondition("active = 1");
        $news=News::model()->news()->findAll($criteria);

            $this->render('index', array(
          'clans' => $clans,
          'players' => $players,
          'news' => $news
        ));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		Yii::app()->theme = 'public';
		$this->bodyClass = 'not-home news-page error-page';

		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest) {
              echo $error['message'];
            } else {
                Yii::app()->getModule('material');
                Yii::app()->getModule('thumbler');
                $criteria = new CDbCriteria();
                $criteria->order = new CDbExpression('RAND()');
                $criteria->limit = 4;
                $articles = RNews::model()->article()->current()->findAll($criteria);

                if ($error['code']==404) {
                    $error_meta = t::get("404 - СТРАНИЦА НЕ НАЙДЕНА");
                    $this->pageTitle = $error_meta;
                    $this->metaDescription = $error_meta;
                } else {
                    $this->pageTitle=Yii::app()->name . ' - Error';
                }

				$this->render('error', array('error'=>$error,'articles'=>$articles));
            }


		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
    $model = new Players();
    $model->attributes = Yii::app()->request->getPost('Players');
    if ($model->login()){
      $ret = array(
        'result'  => true,
        'massage' => t::get('Вход осуществлен, ожидайте...')
      );
    } else {
      $ret = array(
        'result'  => false,
        'massage' => t::get('Не верный логин или пароль.') .' <br><br> <a href="/site/forgot">'. t::get('Восстановить по e-mail?').'</a>'
      );
    }
    echo(CJavaScript::jsonEncode($ret));
    Yii::app()->end();
	}

	public function actionForgot(){
		$this->layout          = '//layouts/home';
		$this->bodyClass       = 'not-home forgot';

		$this->render('forgot', array());
	}

  public function actionRecovery(){
    $model = new Players();
    $model->attributes = Yii::app()->request->getPost('Players');
    $ret = $model->recovery();
    echo(CJavaScript::jsonEncode($ret));
    Yii::app()->end();
  }

  public function actionRegister(){
    $model = new Players();
    $model->attributes = Yii::app()->request->getPost('Players');
    $ret = $model->register();
    echo(CJavaScript::jsonEncode($ret));
    Yii::app()->end();
  }

    public function actionAddemail(){
        $email = Yii::app()->request->getPost('email');
        $user_id = Yii::app()->request->getPost('user_id');
        $ret = array();
        $ret['errors'] = '';
        $ret['result'] = 0;

        $checkEmail = _mysql_num_rows(_mysql_exec("select * from players where email='$email' limit 1"));

        if (!(preg_match("/^([a-zA-Z0-9_]+[a-zA-Z0-9_\.\+-])*@[A-Za-z0-9_]+\.([A-Za-z_\.]*)$/", $email))) {
            $ret['errors'] .= t::get("E-mail введен не корректно.");
        }
        else if ($checkEmail > 0) {
            $ret['errors'] .= t::get('Этот e-mail уже используется другим пользователем. Укажите другой адрес.');
        }

        if ($ret['errors']=='') {
            $model = Players::model()->findByPk($user_id);
            if($model) {
                $model->email = $email;
                if ($model->save(true, array('email'))) {
                    $model->refresh();
                    $ret['result'] = 1;
                } else {
                    $ret['errors'] = $model->getErrors();
                }
            }
        }

        echo(CJavaScript::jsonEncode($ret));
        Yii::app()->end();
    }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		session_unset();
		session_destroy();
		Yii::app()->user->logout();

    ini_set('session.cookie_domain', '.'.BASE_DOMAIN);
    session_set_cookie_params(0, '/', BASE_DOMAIN);
    ini_set('session.save_handler', 'files');
		session_start();
		t::getInstance()->setLanguage(t::iso());
		$this->redirect('/');
	}


  public function actionLang($set = false)
  {
      t::getInstance()->setLanguage($set);

    $redirect = Yii::app()->request->urlReferrer;
    $redirect = $redirect?$redirect:'/';
    $this->redirect($redirect);

    return true;
  }

  public function actionTor(){
    Yii::app()->theme = 'public';
      $this->bodyClass = 'not-home tor-error-page';
      $this->render('tor');
  }

	public function actionBanned($location){
		$reasons = Ban::getReasons($location);
		if ($reasons) {
			$this->render('banned', array(
				'reasons' => $reasons
			));
		} else $this->redirect('/');
	}

	public function actionRobots(){
		$domain = basename(Yii::app()->getBaseUrl(true));
		$model = Robots::model()->findByAttributes(array('domain' => $domain));
		if ($model) {
			header('Content-Type: text/plain;charset=utf-8');
			echo($model->content);
			Yii::app()->end();
		}
		throw new CHttpException(404,'The specified page cannot be found.');
	}

	public function actionSitemap(){
		$language = t::iso();
		$filename = str_replace('http://', '', BASE_DOMAIN);
		$filename = str_replace('https://', '', $filename);
		$filename = str_replace('.', '-', $filename);
		$filename = "sitemap-{$filename}-{$language}.xml";
		$path = Yii::getPathOfAlias('application').'/runtime/sitemap/';

		header('Content-Type: application/xml');
		readfile($path.$filename);
		Yii::app()->end();
	}

	public function actionAuth($provider = false){
		$config_file_path = Yii::getPathOfAlias('application.components').'/hybridauth/hybridauth/config.php';
		require_once( Yii::getPathOfAlias('application.components') . "/hybridauth/hybridauth/Hybrid/Auth.php" );

		if (!$provider) {
			require_once( Yii::getPathOfAlias('application.components') . "/hybridauth/hybridauth/Hybrid/Endpoint.php" );
			Hybrid_Endpoint::process();
		} else {
			try
			{
				$hybridauth = new Hybrid_Auth( $config_file_path );

				$adapter = $hybridauth->authenticate( $provider );
				$user_profile = $adapter->getUserProfile();

				$player = PlayersSocial::model()->findByAttributes(array(
					'provider' => strtolower($provider),
					'identity' => $user_profile->identifier
				));

				if (Yii::app()->stat->model) {
					if ($player) {
						Yii::app()->user->setFlash('error', t::get('<a href="%s">Аккаунт %s</a> уже привязан к <a href="%s">другому пользователю</a>', array($user_profile->profileURL, $provider, "/view.php?view={$player->player_id}")));
					} else {
						require_once Yii::getPathOfAlias('webroot').'/classes/index/Registration_Handler.php';
						$register = new Registration_Handler();
						$user_id = $register->register_player_by_provider($provider, $user_profile);
					}
					echo("<script>window.location='/account.php?view=social';</script>");
				} else {
					if ($player) {
						$_SESSION['userid'] = $player->player_id;
						$_SESSION["user"] = true;
						$_SESSION["pass"] = true;

						Players::model()->findByPk($player->player_id)->popupInvite();
						echo("<script>window.location='/city.php';</script>");
					} else {
						require_once Yii::getPathOfAlias('webroot').'/classes/index/Registration_Handler.php';
						$register = new Registration_Handler();
						$user_id = $register->register_player_by_provider($provider, $user_profile);
						$_SESSION['userid'] = $user_id;
						$_SESSION["user"] = true;
						$_SESSION["pass"] = true;
						$_SESSION['beginner_window'] = true;
                        echo("<script>window.location='/player/invite';</script>");
					}
                }
				die();
			}catch( Exception $e ){
				switch( $e->getCode() ) {
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
				Yii::app()->user->setFlash('login_ error', $message);
				$this->redirect('/');
			}
		}
	}

	public function actionTestbox(){
		if ($id = Yii::app()->request->getParam('id')) {
			$model = PwCosts::model()->findByPk($id);
			Yii::app()->event->pay(Yii::app()->stat->id, $model->platinum, $model);
			echo('Счет пополнен на '.$model->price.'$.<br>');
		}
		echo('<h3>Пополнить счет и выдать ящик:</h3>');
		$costs = PwCosts::model()->findAll();
		foreach($costs as $cost){
			echo(CHtml::link("{$cost->price}$ = {$cost->platinum} крышек", '/site/testbox?id='.$cost->id));
			echo('<br>');
		}
	}

    public function actionUnsubscribe(){
        $email = Yii::app()->request->getParam('email','');

        $error = '';
        $success = '';

        if ($email!='') {
            $ilog = new InviteLog();
            if ($ilog->unsubscribe($email)) {
                $success = t::get('Email %s был успешно отписан от рассылки приглашений.',$email);
            } else {
                $error = t::get('Email %s уже отписан, или не был найден в рассылке приглашений.',$email);
            }

        } else {
            $error = t::get('Email для отписки от рассылки приглашений не был передан.');
        }

        $this->bodyClass = 'not-home unsubsribe-page';
        $this->render('unsubscribe',[
            'error'=>$error,
            'success'=>$success
        ]);
    }

/*
  public function actionTemp3(){
    $transaction = Yii::app()->db->beginTransaction();

    $condition = new CDbCriteria();
    $condition->addCondition("currency = :currency");
    $condition->params[':currency'] = PlayerTrainer::PLATINUM;
    $condition->limit = 1;
//    $condition->order = 'RAND()';

    $one_models = PlayerImplant::model()->findAll($condition);
    if ($one_models) {
      foreach ($one_models as $one_model){
        $player = Players::model()->findByPk($one_model->player_id);

        $condition = new CDbCriteria();
        $condition->addCondition("player_id = :player_id");
        $condition->addCondition("currency = :currency");
        $condition->params[':currency'] = PlayerTrainer::PLATINUM;
        $condition->params[':player_id'] = $one_model->player_id;

        $models = PlayerImplant::model()->findAll($condition);

        $cost = 0; $cnt=0;
        foreach ($models as $one){
          $cost += $this->getCost(PlayerTrainer::PLATINUM, $cnt);
          $cnt++;
          switch($one->type) {
            case PlayerImplantsTypeEnum::AGILITY:
              $player->agility--;
              break;
            case PlayerImplantsTypeEnum::DEFENSE:
              $player->defense--;
              break;
            case PlayerImplantsTypeEnum::MAX_ENERGY:
              $player->max_energy--;
              break;
            case PlayerImplantsTypeEnum::MAX_HP:
              $player->max_hp--;
              break;
            case PlayerImplantsTypeEnum::STRENGTH:
              $player->strength--;
              break;
          }
          $query = " DELETE FROM player_implant WHERE player_id = {$player->id} AND currency = '".PlayerTrainer::PLATINUM."' AND type = '{$one->type}' LIMIT 1";
          if (!Yii::app()->db->createCommand($query)->execute()){
            print_r('Cant remove implant (((');
            $transaction->rollBack();
            die();
          }
        }

        $player->platinum += $cost;
        if (!$player->save(false)){
          print_r($player->getErrors());
          $transaction->rollBack();
          die();
        }

        $mail = new Mail();
        $mail->sender  = 'developer';
        $mail->subject = 'Возврат крышек';
        $mail->body = "{$cost} крышек возвращено за покупку имплантантов (".count($models).") по ошибочной цене. Имплантанты были сняты с персонажа. Вы сможете купить имплантанты по соответствующей цене уже скоро.";
        $mail->unread = 'T';
        $mail->kbox = 'N';
        $mail->senderid = 0;
        $mail->owner = $player->id;
        $mail->senderdel = 'N';
        $mail->ownerdel = 'N';

        echo('#'.$player->id.'<br>');
        echo($mail->body);

        if (!$mail->save()){
          print_r($mail->getErrors());
          $transaction->rollBack();
          die();
        }

        $transaction->commit();
      }

      // echo("<script>document.location.href = '/site/temp3';</script>");
    } else echo('done!');
  }

  public function getCost($currency = PlayerTrainer::GOLD, $buyed = 0){
    switch ($currency) {
      case PlayerTrainer::GOLD:
        return 1000 + 600*$buyed;
        break;
      case PlayerTrainer::PLATINUM:
        return round(5 * pow(1.01, $buyed));
    }
  }
*/
/*
    public function actionTemp2(){
      $limit = 2500;
      $ofst = Yii::app()->request->getParam('ofst', 0);
      $condition = new CDbCriteria();
      $condition->limit = $limit;
      $condition->offset = $ofst;
      $players = Players::model()->findAll($condition);
      $i=0;
      foreach($players as $player) {
        $i++;
        $b = $this->getBuyed($player->id);
        $c = floor($b/2);
        $criteria = new CDbCriteria();
        $criteria->addCondition("player_id = {$player->id}");
        $criteria->addCondition("currency = '".PlayerTrainer::GOLD."'");
        $criteria->limit = $c;
        PlayerImplant::model()->updateAll(array('currency' => PlayerTrainer::PLATINUM), $criteria);
      }
      $ofst = $ofst+$limit;
      if ($i>0) {
        echo("<script>document.location.href = '/site/temp2?ofst=$ofst';</script>");
      } else echo('done!');
    }
    public function actionTemp(){
      $limit = 2500;
      $ofst = Yii::app()->request->getParam('ofst', 0);
      $condition = new CDbCriteria();
      $condition->limit = $limit;
      $condition->offset = $ofst;
      $players = Players::model()->findAll($condition);
      $i = 0;
      foreach($players as $player) {
        while ($this->getBuyed($player->id) < $player->impl) {
          $i++;
          $model= new PlayerImplant();
          $model->player_id = $player->id;
          $model->currency  = PlayerTrainer::GOLD;
          if (!$model->save()) {
            print_r($model->errors); die();
          }
        }
        if($i>1000) break;
      }
      echo("$i added");
      $ofst = ($i == 0)?$ofst+$limit:$ofst;
      echo("<script>document.location.href = '/site/temp?ofst=$ofst';</script>");
    }
  public function getBuyed($playerid) {
    return PlayerImplant::model()->countByAttributes(array(
        'player_id' => $playerid,
        'currency'  => PlayerTrainer::GOLD
    ));
  }
*/
}