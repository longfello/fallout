<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 08.03.16
 * Time: 10:32
 */
class PlayerTrainer
{
  const GOLD     = 'gold';
  const PLATINUM = 'platinum';

  const FREEZE_DEFAULT_DURATION = 10*24*60*60; // 10 дней
  const FREEZE_DEFAULT_PAUSE = 10*24*60*60; // 10 дней
  const FREEZE_MAX_ITERATION = 10;
  /** За сколько дней до окончания можно продлить */
  const FREEZE_PROLONG_DURATION = 3*24*60*60; // 3 дня

  public $freeze_duration = self::FREEZE_DEFAULT_DURATION;

  /** @var int */
  public $level;
  /** @var Players */
  public $player;

  public function __construct(){
    $this->player = Yii::app()->stat->model;
    $this->level  = $this->player->level;
  }

  public function getCost($currency = self::GOLD, $aux=0){
    switch ($currency) {
      case self::GOLD:
        return 1000 + 600* ($this->getBuyed(self::GOLD) + $aux);
        break;
      case self::PLATINUM:
        return round(5 * pow(1.01, $this->getBuyed(self::PLATINUM)));
    }

    // старая формула учитываеи сумму всех статов
    //$cena1 = ($stat[max_hp] + $stat[max_energy] + $stat[agility] + $stat[strength] + $stat[defense] + $stat[ap] + $aux);

    // новая формула учитываеи сумму всех статов 1-го уровня + количество уже воткнутых имплантов
    // для терминаторов-помоечников на первом уровне цена НЕ изменится
    // для старичков цена уменьшится
    /*
    $cena1 = ( 15 + 10 + 3 + 3 + 3 + 3 + $this->player->impl + $aux );

    if($cena1 >= 20000) {
      $cena2 = 250;
    } elseif($cena1 >= 225) {
      $cena2 = 225;
    } elseif($cena1 >= 200) {
      $cena2 = 200;
    } elseif($cena1 >= 175) {
      $cena2 = 175;
    } elseif($cena1 >= 150) {
      $cena2 = 140;
    } elseif($cena1 >= 125) {
      $cena2 = 100;
    } elseif($cena1 >= 100) {
      $cena2 = 80;
    } elseif($cena1 >= 75) {
      $cena2 = 60;
    } elseif($cena1 >= 50) {
      $cena2 = 40;
    } elseif($cena1 >= 25) {
      $cena2 = 25;
    } elseif($cena1 > 1) {
      $cena2 = 1;
    }
    $stoimost = ($cena1* $cena2);

    //тут я взял на себя смелость умножить на 2 а т ослишком дёшево
    $stoimost = $stoimost * 2;
    return $stoimost;
    */
  }

  public function train($type, $currency){
    $cost = $this->getCost($currency);

    switch ($currency) {
      case self::GOLD:
        $moneyOk = (($cost <= $this->player->gold) || ($cost <= $this->player->bank));
        break;
      case self::PLATINUM:
        $moneyOk = $cost <= $this->player->platinum;
        break;
      default:
        $moneyOk = false;
    }

    if (!$moneyOk){
      switch ($currency) {
        case self::GOLD:
          $text = Window::error(t::get('У вас не достаточно золота!'));
          break;
        case self::PLATINUM:
          $text = Window::error(t::get('У вас не достаточно крышек!'));
          break;
        default:
          $text = Window::error(t::get('Операция не выполнена!'));
          break;
      }
    } else {
      if ($this->getAvailable($currency) < 1) {
        $text = Window::error(t::get('Для данного уровня вашего персонажа все импланты исчерпаны!'));
      } else {
        if ($currency == self::GOLD) {
          if ($cost <= $this->player->gold) {
            $this->player->gold -= $cost;
          } else {
            $this->player->bank -= $cost;
          }
        } else {
          $this->player->platinum -= $cost;
        }
        $this->player->$type++;
        $this->player->impl++;
        $ok = $this->player->save(false);
        $this->player->refresh();

        $impl = new PlayerImplant();
        $impl->player_id = $this->player->id;
        $impl->type      = $type;
        $impl->currency  = $currency;
        $ok = $ok && $impl->save();

        if ($ok) {
          $text = Window::highlight(t::get('Операция прошла удачно!'));
        } else {
          $text = Window::error(t::get('Операция не выполнена!'));
        }
      }
    }
    return $text;
  }
  public function getBuyed($currency) {
    return PlayerImplant::model()->countByAttributes(array(
        'player_id' => $this->player->id,
        'currency'  => $currency
    ));
  }

  /**
   * @param string $currency
   * @return int
   */
  function getAvailable($currency = self::GOLD){
    $buyed = $this->getBuyed($currency);
    /**
     *  1-25 уровни (включительно): персонаж может вставить 3 импланта за золото и 3 импланта за крышки на каждом уровне
     *  26-50 уровни (включительно): персонаж может вставить 4 импланта за золото и 4 импланта за крышки
     *  51-200 уровни (включительно): персонаж может вставить 5 имплантов за золото и 5 имплантов за крышки
     */
    $available = min(25, $this->level) * 3;
    if ($this->level > 25) {
      $available += min(25, ($this->level - 25)) * 4;
    }
    if ($this->level > 50) {
      $available += (($this->level - 50) * 5);
    }

    $available -= $buyed;

    return max(0, $available);
  }

  public function getClearExpCost($currency){
	  $basecost = 0;
	  switch($currency){
		  case self::GOLD:
			  if ($this->player->level < 11){
				  $basecost = 44500;
			  } elseif (($this->player->level > 10 ) AND ($this->player->level < 21)){
				  $basecost = 190500;
			  } elseif (($this->player->level > 20 ) AND ($this->player->level < 31)){
				  $basecost = 399500;
			  } elseif (($this->player->level > 30 ) AND ($this->player->level < 41)){
				  $basecost = 849900;
			  } elseif (($this->player->level > 40 ) AND ($this->player->level < 51)){
				  $basecost = 1199000;
			  } elseif (($this->player->level > 50 ) AND ($this->player->level < 61)){
				  $basecost = 7999000;
			  } elseif (($this->player->level > 60 ) AND ($this->player->level < 71)){
				  $basecost = 11999000;
			  } elseif (($this->player->level > 70 ) AND ($this->player->level < 81)){
				  $basecost = 18999000;
			  } elseif (($this->player->level > 80 ) AND ($this->player->level < 91)){
				  $basecost = 26999000;
			  } elseif (($this->player->level > 90 ) AND ($this->player->level < 101)){
				  $basecost = 29999000;
			  } elseif (($this->player->level > 100 ) AND ($this->player->level < 111)){
				  $basecost = 33999000;
			  } elseif (($this->player->level > 110 ) AND ($this->player->level < 121)){
				  $basecost = 44999000;
			  } elseif (($this->player->level > 120 ) AND ($this->player->level < 131)){
				  $basecost = 53999000;
			  } elseif (($this->player->level > 130 ) AND ($this->player->level < 141)){
				  $basecost = 66999000;
			  } elseif (($this->player->level > 140 ) AND ($this->player->level < 151)){
				  $basecost = 84999000;
			  } elseif (($this->player->level > 150 )){
				  $basecost = 109999000;
			  }
			  break;
		  case self::PLATINUM:
			  if ($this->player->level < 11){
				  $basecost = 190;
			  } elseif (($this->player->level > 10 ) AND ($this->player->level < 21)){
				  $basecost = 990;
			  } elseif (($this->player->level > 20 ) AND ($this->player->level < 31)){
				  $basecost = 1990;
			  } elseif (($this->player->level > 30 ) AND ($this->player->level < 41)){
				  $basecost = 2990;
			  } elseif (($this->player->level > 40 ) AND ($this->player->level < 51)){
				  $basecost = 3990;
			  } elseif (($this->player->level > 50 ) AND ($this->player->level < 61)){
				  $basecost = 4990;
			  } elseif (($this->player->level > 60 ) AND ($this->player->level < 71)){
				  $basecost = 5990;
			  } elseif (($this->player->level > 70 ) AND ($this->player->level < 81)){
				  $basecost = 6990;
			  } elseif (($this->player->level > 80 ) AND ($this->player->level < 91)){
				  $basecost = 7990;
			  } elseif (($this->player->level > 90 ) AND ($this->player->level < 101)){
				  $basecost = 8990;
			  } elseif (($this->player->level > 100 ) AND ($this->player->level < 111)){
				  $basecost = 9990;
			  } elseif (($this->player->level > 110 ) AND ($this->player->level < 121)){
				  $basecost = 10990;
			  } elseif (($this->player->level > 120 ) AND ($this->player->level < 131)){
				  $basecost = 11990;
			  } elseif (($this->player->level > 130 ) AND ($this->player->level < 141)){
				  $basecost = 12990;
			  } elseif (($this->player->level > 140 ) AND ($this->player->level < 151)){
				  $basecost = 13990;
			  } elseif (($this->player->level > 150 )){
				  $basecost = 14990;
			  }
	  }
	  return $basecost;
  }
  public function getClearExpAvailable($currency){
	  switch ($currency){
		  case self::GOLD:
		  	return Yii::app()->stat->model->getMeta('clear_exp4gold', 0) != Yii::app()->stat->model->level;
		  case self::PLATINUM:
            return Yii::app()->stat->model->getMeta('clear_exp4platinum', 0) != Yii::app()->stat->model->level;
	  }
	  return false;
  }

  public function clearExp($currency){
	  switch($currency){
		  case self::GOLD:
			  $this->player->gold -= $this->getClearExpCost($currency);
			  Yii::app()->stat->model->setMeta('clear_exp4gold', Yii::app()->stat->model->level);
			  break;
		  case self::PLATINUM:
			  $this->player->platinum -= $this->getClearExpCost($currency);
			  Yii::app()->stat->model->setMeta('clear_exp4platinum', Yii::app()->stat->model->level);
			  break;
	  }
	  $this->player->exp   = 0;
	  $this->player->save(false);
  }

  public function isFreezeExpAvailable(){
	  $until = Yii::app()->stat->model->getMeta('freeze_exp_until', 0);
	  if (($until < time()) ||                                                            // закончилось время действия
	      ((($until > time()) && ($until < time() + self::FREEZE_PROLONG_DURATION))) || // не более трех дней до окончания
	      ($until == 0)) {                                                              // еще не было покупок
          return true;
	  }
	  return false;
  }

  public function getFreezeExpCost(){
	  $iteration = Yii::app()->stat->model->getMeta('freeze_exp_count', 0);

      $until = Yii::app()->stat->model->getMeta('freeze_exp_until', 0);
      if ($until+self::FREEZE_DEFAULT_PAUSE < time()) {
          $iteration = 0;
      }

      if ($iteration > self::FREEZE_MAX_ITERATION) {
          $iteration = self::FREEZE_MAX_ITERATION;
      }

	  $koef = 1.2; $basecost = 11900;
	  if ($this->player->level < 11){
		  $koef = 1.2; $basecost = 11900;
	  } elseif (($this->player->level > 10 ) AND ($this->player->level < 21)){
		  $koef = 1.2; $basecost = 13900;
	  } elseif (($this->player->level > 20 ) AND ($this->player->level < 31)){
		  $koef = 1.3; $basecost = 24900;
	  } elseif (($this->player->level > 30 ) AND ($this->player->level < 41)){
		  $koef = 1.3; $basecost = 29500;
	  } elseif (($this->player->level > 40 ) AND ($this->player->level < 51)){
		  $koef = 1.4; $basecost = 35900;
	  } elseif (($this->player->level > 50 ) AND ($this->player->level < 61)){
		  $koef = 1.4; $basecost = 39700;
	  } elseif (($this->player->level > 60 ) AND ($this->player->level < 71)){
		  $koef = 1.5; $basecost = 44800;
	  } elseif (($this->player->level > 70 ) AND ($this->player->level < 81)){
		  $koef = 1.6; $basecost = 52300;
	  } elseif (($this->player->level > 80 ) AND ($this->player->level < 91)){
		  $koef = 1.6; $basecost = 59500;
	  } elseif (($this->player->level > 90 ) AND ($this->player->level < 101)){
		  $koef = 1.7; $basecost = 65300;
	  } elseif (($this->player->level > 100 ) AND ($this->player->level < 111)){
		  $koef = 1.7; $basecost = 72500;
	  } elseif (($this->player->level > 110 ) AND ($this->player->level < 121)){
		  $koef = 1.8; $basecost = 79900;
	  } elseif (($this->player->level > 120 ) AND ($this->player->level < 131)){
		  $koef = 1.8; $basecost = 85300;
	  } elseif (($this->player->level > 130 ) AND ($this->player->level < 141)){
		  $koef = 1.9; $basecost = 89700;
	  } elseif (($this->player->level > 140 ) AND ($this->player->level < 151)){
		  $koef = 1.9; $basecost = 99200;
	  } elseif (($this->player->level > 150 )){
		  $koef = 2.0; $basecost = 124500;
	  }

	  return $iteration?round($basecost*(pow($koef,$iteration))):$basecost;
  }

  public function freezeExp(){
	  $until = Yii::app()->stat->model->getMeta('freeze_exp_until', 0);
	  if ($until+self::FREEZE_DEFAULT_PAUSE < time()) {
          $iteration = 0;
      } else {
          $iteration = Yii::app()->stat->model->getMeta('freeze_exp_count', 0);
      }
      $until = ($until > time())?$until:time();

	  $this->player->gold -= $this->getFreezeExpCost();
	  $this->player->save(false);

	  cron_freeze_exp_reminder::schedule($until + $this->freeze_duration - self::FREEZE_PROLONG_DURATION, [
	  	'player_id' => $this->player->id,
		'date'      => date('d.m.Y H:i', $until + $this->freeze_duration)
	  ]);

	  Yii::app()->stat->model->setMeta('freeze_exp_until', $until + $this->freeze_duration);
	  Yii::app()->stat->model->setMeta('freeze_exp_count', $iteration+1);
  }
}