<?php

class DefaultController extends ModuleController
{
    public $pageTitle = 'Чат';

    public $modelName = 'Chat';
    private $_model;


    public function init()
    {
        $this->pageTitle = t::get('Чат');
        parent::init();


        // Если игрок не в клане
        if (!Yii::app()->stat->clan)
        {
            $this->redirect('/chat');
        }


        $chatPlayer = ChatPlayer::model()->findByPk(Yii::app()->stat->id);
        // Если записи в базе не существует, то добавляем её
        if (!$chatPlayer)
        {
            $model = new ChatPlayer();
            $model->player_id = Yii::app()->stat->id;
            $model->last_activity = new CDbExpression('NOW()');
            $model->save();
        }
        // Если запись в базе существует, то обновляем параметр активности
        else
        {
            $chatPlayer->last_activity = new CDbExpression('NOW()');
            $chatPlayer->save();
        }

    }


	public function actionIndex()
	{
        $result = Yii::app()->theme = 'main';

      Yii::app()->clientScript->registerScriptFile(
        Yii::app()->assetManager->publish(
          Yii::getPathOfAlias('webroot') . "/js/i18n/".t::iso()."/lang.js"
        )
      );
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR .  'jquery.caret.js'
            )
        );

    Yii::app()->clientScript->registerScript('chat',"
var rchatLang = {
  confirmDelete: '".t::encJs('Удалить выбранное сообщение?')."',
  deleteMsg: '".t::encJs('Удалить')."',
  privat: '".t::encJs('лично для')."',
  ignore2player: '".t::encJs('Данный игрок добавлен вами в игнор')."',
  ignore2me : '".t::encJs('Данный игрок добавил вас в игнор')."',
  banUntil: '".t::encJs('Бан в чате до')."',
};
", CClientScript::POS_HEAD);

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('chatclan') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR .  'rchat.js'
            )
        );

        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('chatclan') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'rchat.css'
            )
        );


        $chatModel = new RChat();
		$this->render('index', array('model' => $chatModel));

	}

}