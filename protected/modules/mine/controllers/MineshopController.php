<?php

class MineshopController extends ModuleController
{
    public $pageTitle = 'Серный рудник';
    public $metaDescription = '';

    public function init()
    {
        Yii::app()->theme = 'main';
        $this->pageTitle = t::get('Серный рудник');

        // Обнулить записи на серном руднике, если прошли сутки
        Mine::model()->resetWorkWay();
        parent::init();
    }


    public function actionIndex()
    {
        $remainingCountWays = Mine::model()->getRemainingWays();

        $this->render('index', array('count' => (int) $remainingCountWays));
    }


    public function actionBuy()
    {
        if (!Mine::model()->isEnoughPlatinum())
        {
            Yii::app()->user->setFlash('error', t::get('У вас недостаточно средств!'));
        }
        elseif (!Mine::model()->isAvailableDonate())
        {
            Yii::app()->user->setFlash('error', t::get('Хватит на сегодня! Сходи лучше наточи кирку!'));
        }
        else
        {
            /**
             * @var $mine Mine
             */
            $mine  = Mine::model()->findByPk(Yii::app()->stat->id);
            if (!$mine)
            {
                $mine = new Mine();
                $mine->player_id = Yii::app()->stat->id;
                $mine->time = new CDbExpression('NOW()');
            }
            $mine->is_donate = 1;
            $mine->save(false);

            // Снять крышки со счёта игрока
            Players::model()->updateByPk(
                Yii::app()->stat->id,
                array('platinum' => (Yii::app()->stat->platinum - Yii::app()->params['mine']['costWays'])
            ));

            Mine::model()->updateByPk(
                Yii::app()->stat->id,
                array('paid_donate_time' => (new CDbExpression('NOW()')))
            );

            Yii::app()->user->setFlash('success', t::get('Инъекция сделана. Количество действий на руднике увеличено.'));

        }

        $this->redirect(array('index'));
    }

}