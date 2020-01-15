<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 27.10.16
 * Time: 11:21
 */
class GameEvent extends CComponent {

	public function init(){

	}

	public function pay($playerId, $platinum, $costModel){
		$event = new CModelEvent(null, [
			'player_id' => $playerId,
			'amount'    => $platinum,
			'model'     => $costModel
		]);
		$this->onAfterPay($event);
	}
	public function killMob($playerId, $enemies, $data = []){
		$event = new CModelEvent(null, [
			'player_id'   => $playerId,
			'enemies'     => $enemies,
			'data'        => $data,
		]);
		$this->onKillMob($event);
	}



	public function onAfterPay($event) {
		// Непосредственно вызывать событие принято в его описании.
		// Это позволяет использовать данный метод вместо raiseEvent
		// во всём остальном коде.
		$this->raiseEvent('onAfterPay', $event);
	}

	public function onKillMob($event) {
		// Непосредственно вызывать событие принято в его описании.
		// Это позволяет использовать данный метод вместо raiseEvent
		// во всём остальном коде.
		$this->raiseEvent('onKillMob', $event);
	}
}