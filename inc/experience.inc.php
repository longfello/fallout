<?php

global $stat;

/* Для персонажа */
$expn = Extra::getUserExperienceBeforeNextLevel();
/* Для питомца */
if (isset($stat['PET'])) {
	if (Yii::app()->stat && Yii::app()->stat->id){
		if(!Yii::app()->stat->model) Yii::app()->stat->init();
		$expn_pet = Extra::getPetExperienceBeforeNextLevel(Yii::app()->stat->model);
	}
}




if ($stat['opit'] >= 10000) {
    $jobgain = 10;
    $exptolvlrab = '--';
} elseif ($stat['opit'] >= 7500) {
    $jobgain = 9;
    $exptolvlrab = 10000 - $stat['opit'];
} elseif ($stat['opit'] >= 6000) {
    $jobgain = 8;
    $exptolvlrab = 7500 - $stat['opit'];
} elseif ($stat['opit'] >= 4500) {
    $jobgain = 7;
    $exptolvlrab = 6000 - $stat['opit'];
} elseif ($stat['opit'] >= 3000) {
    $jobgain = 6;
    $exptolvlrab = 4500 - $stat['opit'];
} elseif ($stat['opit'] >= 1500) {
    $jobgain = 5;
    $exptolvlrab = 3000 - $stat['opit'];
} elseif ($stat['opit'] >= 800) {
    $jobgain = 4;
    $exptolvlrab = 1500 - $stat['opit'];
} elseif ($stat['opit'] >= 300) {
    $jobgain = 3;
    $exptolvlrab = 800 - $stat['opit'];
} elseif ($stat['opit'] >= 65) {
    $jobgain = 2;
    $exptolvlrab = 300 - $stat['opit'];
} elseif($stat['opit'] > -1) {
    $jobgain = 1;
    $exptolvlrab = 65-$stat['opit'];
}
    $opitrabotnika = $stat['opit'];

$perk3=0;
if($stat['level'] > 0) {
    $perk3 = 1;
}
$perkk3 = (2 - $perk3);

