<?php

class FoodController extends Controller
{
    public function actionIndex()
    {
        $outposts = Outposts::model()->find('owner = :owner', array(':owner' => Yii::app()->stat->id));

        if (!$outposts)
        {
            $this->render('noWater');
        }
        else
        {
            /**
             * Вывод всех предметов для продажи на страницу
             * @var $command CDbCommand
             */
            $items = array();
            $command = Yii::app()->db->createCommand();
            $command->select('e.*, c.name AS clan_name, ifnull(c.id,0) AS clan_id');
            $command->from('equipment AS e');
            $command->leftJoin('clans AS c', 'c.id = e.clan');
            $command->where("e.mtype='W' AND e.status = 'S' AND e.owner = 0 AND e.shoplvl <= 0");
            $command->order('e.type DESC, e.cost ASC');
            $command->setFetchMode(PDO::FETCH_OBJ);
            $items = $command->queryAll();

            $this->render('index', array('items' => $items));
        }
    }


    public function actionBuy()
    {
        $request = Yii::app()->request;
        $id = $request->getPost('id');
        $count = $request->getPost('count');

        /**
         * Получить данные о покупаемом предмете
         * @var $command CDbCommand
         */
        $item = array();
        $command = Yii::app()->db->createCommand();
        $command->select('e.*, c.name AS clan_name, ifnull(c.id,0) AS clan_id');
        $command->from('equipment AS e');
        $command->leftJoin('clans AS c', 'c.id = e.clan');
        $command->where("e.mtype='W' AND e.status = 'S' AND e.owner = 0 AND e.shoplvl <= 0 AND e.id = :id", array(':id' => $id));
        $command->order('e.type DESC, e.cost ASC');
        $command->setFetchMode(PDO::FETCH_OBJ);
        $item = $command->queryRow();

        $item->name = t::getDb('name', 'equipment', 'id', $item->id);
        $item->opis = t::getDb('opis', 'equipment', 'id', $item->id);

      // Проверяем, можем ли купить предмет
        if ($error = $this->_getErrorItem($item, $count))
        {
            Yii::app()->user->setFlash('buyError', $error);
            $this->redirect($this->createUrl('index'));
        }
        // Покупка предмета
        else
        {
            // Добавляем предметы в базу
            $uequipment = new Uequipment();
            for ($i = 0; $i < $count; $i++)
            {
                $uequipment->isNewRecord = true;

                $uequipment->id = null;
                $uequipment->owner = Yii::app()->stat->id;
                $uequipment->item = $item->id;
                if ($uequipment->validate())
                {
                    $uequipment->save();
                }
            }

            // Списываем воду из базы
            $outposts = Outposts::model()->find('owner = :owner', array(':owner' => Yii::app()->stat->id));
            $outposts->tokens = $outposts->tokens - $item->cost * $count;
            if ($outposts->validate())
            {
                $outposts->save();
            }

            // Добавить сообщение об успешной покупке
            $msg = t::get('Вы приобрели <b>%sx %s</b> за <b>%s</b> воды<img src="/images/tokens.png" alt="Вода">.', array($count, $item->name, $item->cost * $count));
            Yii::app()->user->setFlash('buySuccess', $msg);
            $this->redirect(array('index'));
        }
    }


    /**
     * Ошибки, которые не позволяют купить предмет
     * @param $item
     * @param $count
     * @return string
     */
    private function _getErrorItem($item, $count)
    {
        // Наличие предмета в магазине
        if (!$item)
        {
            return t::get("Что то я не вижу такого предмета в магазине.");
        }
        elseif ($item->clan_id && $item->clan_id != Yii::app()->stat->clan)
        {
            return t::get('Сожалею, этот товар предназначен только для членов клана <b><img src="images/clans/%s.gif">%s</b>', array($item->clan_id, $item->clan_name));
        }
        elseif ($item->cost * $count > Yii::app()->stat->tokens)
        {
            return t::get('К сожалению, вы не можете себе это позволить. У вас не хватает воды<img src="/images/tokens.png" alt="Вода">.').t::get('В своём <a href="/home.php">частном доме</a> вы можете пополнить запасы или купить воду<img src="/images/tokens.png" alt="Вода"> за крышки в <a href="/pshop.php">магазине Барыга</a>.');
        }
        elseif (Tool::questItemIsBlocked(Yii::app()->stat->id, $item->id))
        {
            return t::get("К сожалению, вы не можете купить этот предмет, так как имеете активный квест на его сбор.");
        }
    }
}