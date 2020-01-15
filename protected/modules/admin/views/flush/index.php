<?php
/* @var $this Controller */
?>
<?
echo CHtml::beginForm(array('assets'), 'get');
echo CHtml::submitButton('Очистить ресурсы', array('class' => 'btn btn-primary'));
echo CHtml::endForm();
?>

<br/>

<?
echo CHtml::beginForm(array('cache'), 'get');
echo CHtml::submitButton('Очистить кэш', array('class' => 'btn btn-primary'));
echo CHtml::endForm();
?>
