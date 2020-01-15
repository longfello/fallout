<?php

class DefaultController extends ModuleController
{
    public $pageTitle = 'Чат';

    public $modelName = 'Chat';
    private $_model;


    public function init()
    {

        parent::init();

        $chatPlayer = ChatPlayer::model()->findByPk(Yii::app()->stat->id);
        // Если записи в базе не существует, то добавляем её
        if (!$chatPlayer)
        {
            $model = new ChatPlayer();
            $model->player_id = Yii::app()->stat->id;
            $model->last_activity = new CDbExpression('NOW()');
            $model->lang_slug = Yii::app()->stat->lang_slug;
            $model->save();
        }
        // Если запись в базе существует, то обновляем параметр активности
        else
        {
            $chatPlayer->last_activity = new CDbExpression('NOW()');
            $chatPlayer->lang_slug = Yii::app()->stat->lang_slug;
            $chatPlayer->save();
        }

    }


	public function actionIndex()
	{
        Yii::app()->theme = 'main';
		ChatPlayer::model()->updateByPk(Yii::app()->stat->id, array('last_activity' => new CDbExpression('NOW()'),'lang_slug' =>Yii::app()->stat->lang_slug));

		Players::SendCMD('all', 'chat_reload_users_list');

    Yii::app()->clientScript->registerScript('chat', "
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
          Yii::getPathOfAlias('webroot') . "/js/i18n/".t::iso()."/lang.js"
        )
      );
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR .  'jquery.caret.js'
            )
        );

        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('chat') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR .  'rchat2.js'
            )
        );

        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('chat') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'rchat.css'
            )
        );


        $chatModel = new RChat();
		$this->render('index', array('model' => $chatModel));

	}

}