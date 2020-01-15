<?php

class DefaultController extends ModuleController
{
    public $pageTitle = 'Серный рудник';
    public $metaDescription = '';


    public function init()
    {
        Yii::app()->theme = 'main';

        $this->pageTitle = t::get('Серный рудник');

        // Сделать запись, если это первый вход
        Mine::model()->createData();

        // Обнулить записи на серном руднике, если прошли сутки
        Mine::model()->resetWorkWay();

        $player = Players::model()->findByPk(Yii::app()->stat->id);
        $player->setMeta('cave_district','mine');

        parent::init();
    }



    public function actionIndex()
    {
        //echo ExperienceWorker::model()->getLevel();

        $this->render('index');
    }


    public function actionOffer()
    {
        // Если ли у игрока кирка для работы
        if (!Mine::model()->hasPick())
        {
            Yii::app()->user->setFlash('error', t::get('Для работы на руднике у тебя должна быть кирка!'));

            $this->redirect(array('index'));
        }

        // Если не надета кирка
        if (!Mine::model()->isTakenPick())
        {
            Yii::app()->user->setFlash('error', t::get('Кирка есть, а работать как ей? Может ты и руки в карманах держать будешь?'));

            $this->redirect(array('index'));
        }

        if (Yii::app()->stat->hp <= 0){
          Yii::app()->user->setFlash('error', t::get('Тебе приходилось когда-нибудь видеть работающего мертвеца? Хм... Вот и мне нет. Сначала сходи и восстанови здоровье. Это можно сделать или в магазине "%s", купив подходящие продукты, или подыскать что-то на %s.', array(CHtml::link(t::get('Токсические товары'), 'item_shop_caves.php'), CHtml::link(t::get('Местном рынке'), 'imarket_caves.php'))));
          $this->redirect(array('index'));
        }


        $messages = MineMessage::model()->findAll('type = :type', array(':type' => 0));
        $remainingCountWays = Mine::model()->getRemainingWays();

        shuffle($messages);
        foreach($messages as $message) {
          $message->message = t::getDb('message', 'rev_mine_message', 'message_id', $message->message_id);
        }

        $this->render('offer', array('messages' => $messages, 'count' => $remainingCountWays));

    }


    public function actionWork($id)
    {
        /**
         * @var $message MineMessage
         */
        $message = MineMessage::model()->findByPk($id);

        if (!$message)
        {
          throw new CHttpException(404, t::get('Указанная запись не найдена'));
          die();
        }

        $message->message = t::getDb('message', 'rev_mine_message', 'message_id', $message->message_id);


        if ($message->hp_zero)
        {
            AchieveToPlayer::set('bawdry');
            Players::model()->updateByPk(Yii::app()->stat->id, array('hp' => 0));
            Yii::app()->user->setFlash('error', t::get('Роботу совсем не понравилось это предложение. Теперь придется лечиться, чтобы продолжить работу...'));
        }
        elseif ($message->back)
        {
            $this->redirect(array('index'));
        }
        elseif (Yii::app()->stat->energy <= 0)
        {
            Yii::app()->user->setFlash('error', t::get('Нужно-восстановить-энергию-текст', array(CHtml::link(t::get('Токсические товары'), '/item_shop_caves.php'), CHtml::link(t::get('Местном рынке')), '/imarket_caves.php')));
        }
        elseif (Yii::app()->stat->hp <= 0)
        {
          Yii::app()->user->setFlash('error', t::get('Тебе приходилось когда-нибудь видеть работающего мертвеца? Хм... Вот и мне нет. Сначала сходи и восстанови здоровье. Это можно сделать или в магазине "%s", купив подходящие продукты, или подыскать что-то на %s.', array(CHtml::link(t::get('Токсические товары'), 'item_shop_caves.php'), CHtml::link(t::get('Местном рынке'), 'imarket_caves.php'))));
        }
        else
        {
            // Проверка: доступны ли рудники
            if (Mine::model()->getRemainingWays())
            {
                // Добавить серу в инвентарь
                $sulfurCount = Mine::model()->addSulfur();

                // Добавить опыт для профессии
                ExperienceWorker::addExperience($sulfurCount);


                /** @var $achieveToPlayer AchieveToPlayer */
                $achieveToPlayer = AchieveToPlayer::model()->loadModel('sulfur');
                // Добавить опыт для ачивки
                $achieveToPlayer->addExperience($achieveToPlayer);
                // Добавить ачивку игроку, если он её достиг
                AchieveToPlayer::set('sulfur');

                // Запись о ходе выолненной работы
                Mine::model()->addWorkWay();

                // Добавить сообщение об удачном выполнении работы
                Yii::app()->user->setFlash('completed', $message->getSuccessMessage($sulfurCount));

                // Снять 1 единицу энергии
                Players::model()->updateByPk(Yii::app()->stat->id, array('energy' => --Yii::app()->stat->energy));
            }
            else
            {
                Yii::app()->user->setFlash('error', t::get('Вы израсходовали максимальное количество действий в руднике за текущие сутки'));
            }
        }
        $this->redirect(array('offer'));
    }
}