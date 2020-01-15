<?php
/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 22.09.16
 * Time: 11:24
 */

/**
 * @var $players array
 * @var $this DefaultController
 */

foreach($players as $player) {
	$this->renderPartial('/default/__player', array('player' => $player));
}
