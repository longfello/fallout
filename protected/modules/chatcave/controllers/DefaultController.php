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


        // Если это не пещеры, то выкинуть игрока отсюда
        $cavesLocations = array('/labyrinth.php', '/caves.php');
        if (!in_array(Yii::app()->stat->travel_place, $cavesLocations))
        {
            $this->redirect('/city.php');
            Yii::app()->end();
        }

        $player = Players::model()->findByPk(Yii::app()->stat->id);
        $player->setMeta('cave_district','chat');

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
  notInCaves: '".t::encJs('Вы находитесь не в пещерах')."',
  confirmDelete: '".t::encJs('Удалить выбранное сообщение?')."',
  delete: '".t::encJs('Удалить')."',
  privat: '".t::encJs('лично для')."',
  ignore2player: '".t::encJs('Данный игрок добавлен вами в игнор')."',
  ignore2me : '".t::encJs('Данный игрок добавил вас в игнор')."',
  banUntil: '".t::encJs('Бан в чате до')."',
};
", CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('chatcave') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR .  'rchat.js'
            )
        );

        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('chatcave') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'rchat.css'
            )
        );


        $chatModel = new RChat();
		$this->render('index', array('model' => $chatModel));

	}

}