<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<p><?= CHtml::link(t::get('Конвертер таблицы experience_worker'), $this->createUrl('worker/index')) ?></p>