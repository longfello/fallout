<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 27.10.16
 * Time: 11:39
 */
class PaymentEventHandler {
	public static function afterPay($event){
		$platinum   = $event->params['amount'];
		$playerId   = $event->params['player_id'];
		$model      = $event->params['model'];

		$player = Players::model()->findByPk($playerId);
		if ($player){
			$player->platinum += $platinum;
			$player->save(false);
			logdata_platinum_buying::add($playerId, $platinum);

			self::__processReferals($player, $platinum, $model);
			self::__processKNR($player, $platinum, $model);
			self::__processPopups($player, $platinum, $model);
			self::__processFbPixel($player, $platinum, $model);
		}
	}

	private static function __processFbPixel($player, $platinum, $model){
		/** @var $model PwCosts */
		Popup::add($player->id, "fbq('track', 'Purchase', {value: '{$model->price}', currency: 'USD', content_name: 'Сups', num_items: $platinum} );", false, Popup::ACTION_EXECUTE_JS);
	}

	private static function __processPopups($player, $platinum, $model){
		$box = self::__processBoxes($player, $platinum, $model);
		if ($box){
			Popup::add($player->id, 'Оплата получена, Вам начислено %s крышек. В качестве дополнительного бонуса в ваш личный инвентарь выдан предмет: %s. Администрация', [$platinum, $box->name]);
		} else {
			Popup::add($player->id, 'Оплата получена, Вам начислено %s крышек', [$platinum]);
		}
	}

	private static function __processReferals(Players $player, $platinum, $model){
		// Добавляем крышки игроку реферала
		if ($player->ref) {
			$referal = Players::model()->findByPk($player->ref);
			if ($referal) {
				$refPlatinum = round(0.3 * $platinum);
				$referal->platinum += $refPlatinum;
				$referal->save();
				logdata_platinum_ref::add($referal->id, $refPlatinum, $player->id);
			}
		}
	}
	private static function __processBoxes(Players $player, $platinum, $model){
		$box = false;
		/** @var $box PwCostsBox */
		if ($model) {
			/** @var $model PwCosts */
			if ($model->boxes) {
				$step = ($model->box_type == PwCosts::BOX_TYPE_FORCE_BOX)?10:1;

				while($step>0 && !$box){
					foreach ($model->boxes as $one) {
						if ($one->chance >= rand(0, 100)){
							$box = $one;
							break;
						}
					}
					$step--;
				}
				if (!$box && $model->box_type == PwCosts::BOX_TYPE_FORCE_BOX) {
					$key = array_rand($model->boxes);
					$box = $model->boxes[$key];
				}
			}
		}

		if ($box){
			$equipment = $box->getEquipment();
			$ue = new Uequipment();
			$ue->owner = $player->id;
			$ue->item = $equipment->id;
			$ue->save();
			logdata_platinum_buying_bonus::add($player->id, $equipment->id);
			return $equipment;
		}
		return false;
	}

	private static function __processKNR(Players $player, $platinum, $model){
		if ($event = NkrEvents::getCurrentEvent()){
			$dollars = $player->getNkrCount();
			$query = "
SELECT `id`, `bonus`, `sum` FROM rev_nkr_prices 
WHERE event_id = {$event->event_id}
AND sum <= {$dollars}  
ORDER BY mobs DESC
LIMIT 1";
			$row = Yii::app()->db->createCommand($query)->queryRow();
			if ($row && $row['bonus']) {
				$spent = $player->getMeta(PlayersMeta::KEY_NKR_DOLLARS_SPENT, 0);
				$spent += $row['sum'];
				$player->setMeta(PlayersMeta::KEY_NKR_DOLLARS_SPENT, $spent);

				$bonus = ceil($row['bonus']*$platinum/100);
				$player->platinum += $bonus;
				$player->save();

				logdata_platinum_buying_bonus_knr::add($player->id, $row['sum'], $bonus);
			}
		}
	}

}