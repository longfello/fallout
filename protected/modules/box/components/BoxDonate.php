<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 28.10.16
 * Time: 12:22
 */
class BoxDonate extends BoxPrototype {
	public $box_id;
	/** @var PwCostsBox */
	public $box;

	public function __construct( Equipment $equipment, $params ) {
		parent::__construct( $equipment, $params );
		$this->box = PwCostsBox::model()->findByPk($this->box_id);
	}

	public function use(){
		$items = [];
		$content = false;

		foreach ($this->box->contents as $one){
			if ($one->chance >= rand(0, 100)){
				$content = $one;
				break;
			}
		}

		if ($content) {
			// Если таки что-то выпало...

			// Формируем на основании вероятностей перечень предметов в ящике...
			foreach ($content->items as $item){
				if ($item->chance >= rand(0, 100)){
					$items[] = $item;
				}
			}

			$addedItems = [];
			if ($items) {
				$texts = [];
				foreach ($items as $item){
					/** @var $item PwCostsContentItems */
					$count = ($item->count_rule == PwCostsContentItems::COUNT_RULE_EXACT)?$item->count:rand(1,$item->count);

					$ctype = '1=1';
					switch ($item->type) {
						case 'weapon':
							$ctype = "type='W'";
							break;
						case 'armor':
							$ctype = "type='A'";
							break;
						case 'equipment':
							$ctype = "type='T' or type='U'";
							break;
						case 'food':
							$ctype = "type='F'";
							break;
						case 'energy':
							$ctype = "type='D'";
							break;
					}


					switch ($item->type) {
						case PwCostsContentItems::TYPE_ARMOR:
						case PwCostsContentItems::TYPE_EQUIPMENT:
						case PwCostsContentItems::TYPE_FOOD:
						case PwCostsContentItems::TYPE_WEAPON:
						case PwCostsContentItems::TYPE_ENERGY:
							if ($item->rule == PwCostsContentItems::RULE_ANY) {
								$query = "SELECT id FROM equipment WHERE $ctype ORDER BY rand() LIMIT 1";
							} else {
								$item->list = $item->list?$item->list:'0';
								$query = "SELECT id FROM equipment WHERE id IN ({$item->list}) AND ($ctype) ORDER BY rand() LIMIT 1";
							}
							$eid = Yii::app()->db->createCommand($query)->queryScalar();
							$equipment = Equipment::model()->findByPk($eid);
							if ($equipment){
								$texts[] = $count > 1 ? $equipment->name.' x '.$count : $equipment->name;

								for($i=0; $i<$count; $i++){
									$ue = new Uequipment();
									$ue->owner = $this->player->id;
									$ue->item = $equipment->id;
									$ue->save();
								}
							}
							break;
						case PwCostsContentItems::TYPE_GOLD:
							$texts[] = t::get('<b>%s</b> <img src="/images/gold.png"> золота', array($count));
							$this->player->gold += $count;
							$this->player->save(false);
							break;
						case PwCostsContentItems::TYPE_PLATINUM:
							$texts[] = t::get('<b>%s</b> <img src="/images/platinum.png"> крышек', array($count));
							$this->player->gold += $count;
							$this->player->save(false);
							break;
					}
					$addedItems[] = $item->id;
				}

				// Собираем все итемы для вывода лучшего предложения
				$best     = [];
				$bestText = '';
				$chance   = 100;
				foreach ($this->box->contents as $content){
					foreach ($content->items as $item) {
						if ($chance && !in_array($item->id, $addedItems)){
							$best[$item->chance] = $item;
							$chance = $item->chance;
						}
					}
				}
				krsort($best);
				if ($best){
					$count = ceil(count($best)/3);
					$count = max($count, 2);
					$count = min($count, count($best));
					$names = [];
					for($i=0; $i<$count; $i++){
						$item = array_pop($best);
						$names[] = $this->getMaxBonus($item);
					}
					$names = implode(', ', $names);
					$bestText = '<br>'.t::get('Есть сведения, что в этом ящике могло быть следующее: %s', array($names));
				}

				$texts = implode(', ', $texts).$bestText;
				$alert = Window::highlight(t::get("Вы открыли %s. Вам в инвентарь добавлено: %s", array($this->equipment->name, $texts)));
				return $alert;
			}
		}

		// Нету ничего ((
		$alert = Window::highlight(t::get("Вы открыли %s. К сожалению, там пусто.", array($this->equipment->name)));
		return $alert;
	}


	private function getMaxBonus($item){
		$texts = [];
		$count = ($item->count_rule == PwCostsContentItems::COUNT_RULE_EXACT)?$item->count:rand(1,$item->count);

		$ctype = '1=1';
		switch ($item->type) {
			case 'weapon':
				$ctype = "type='W'";
				break;
			case 'armor':
				$ctype = "type='A'";
				break;
			case 'equipment':
				$ctype = "type='T' or type='U'";
				break;
			case 'food':
				$ctype = "type='F'";
				break;
			case 'energy':
				$ctype = "type='D'";
				break;
		}


		switch ($item->type) {
			case PwCostsContentItems::TYPE_ARMOR:
			case PwCostsContentItems::TYPE_EQUIPMENT:
			case PwCostsContentItems::TYPE_FOOD:
			case PwCostsContentItems::TYPE_WEAPON:
			case PwCostsContentItems::TYPE_ENERGY:
				if ($item->rule == PwCostsContentItems::RULE_ANY) {
					$query = "SELECT id FROM equipment WHERE $ctype ORDER BY mtype='P' DESC, cost DESC LIMIT 1";
				} else {
					$item->list = $item->list?$item->list:'0';
					$query = "SELECT id FROM equipment WHERE id IN ({$item->list}) AND ($ctype) ORDER BY mtype='P' DESC, cost DESC LIMIT 1";
				}
				$eid = Yii::app()->db->createCommand($query)->queryScalar();
				$equipment = Equipment::model()->findByPk($eid);
				if ($equipment){
					$texts[] = $count > 1 ? $equipment->name.' x '.$count : $equipment->name;
				}
				break;
			case PwCostsContentItems::TYPE_GOLD:
				$texts[] = t::get('<b>%s</b> <img src="/images/gold.png"> золота', array($count));
				break;
			case PwCostsContentItems::TYPE_PLATINUM:
				$texts[] = t::get('<b>%s</b> <img src="/images/platinum.png"> крышек', array($count));
				break;
		}
		return implode(', ', $texts);
	}
}