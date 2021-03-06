<?
/**
 * @var $this Controller
 * @var $model CombatRound
 * @var $form CActiveForm
 */
?>
<div class="round">
<? $form = $this->beginWidget('CActiveForm', array(
    'id' => 'attackForm-' . $model->round,
    'htmlOptions' => array('class' => 'attackForm')
)) ?>
    <table class="tab">
    <tr>
        <td colspan="4" align="center"><?= t::get('Раунд %s', $model->round) ?></td>
    </tr>
    <tr>
         <td colspan="4" align="center"><?= t::get('Ваш ход') ?></td>
    </tr>
    <tr>
         <td colspan="2"><?= t::get('Куда ставить блок:') ?></td>
         <td colspan="2"><?= t::get('Куда бить:') ?></td>
     </tr>
    <tr>
        <td><?= $form->label($model, 'block_head') ?></td>
        <td><?= $form->checkBox($model, 'block_head', array('class' => 'blockInput', 'disabled' => 'disabled')) ?></td>
        <td><?= $form->label($model, 'attack_head') ?></td>
        <td><?= $form->checkBox($model, 'attack_head', array('class' => 'attackInput', 'disabled' => 'disabled')) ?></td>
     </tr>
    <tr>
        <td><?= $form->label($model, 'block_hand') ?></td>
        <td><?= $form->checkBox($model, 'block_hand', array('class' => 'blockInput', 'disabled' => 'disabled')) ?></td>
        <td><?= $form->label($model, 'attack_head') ?></td>
        <td><?= $form->checkBox($model, 'attack_hand', array('class' => 'attackInput', 'disabled' => 'disabled')) ?></td>
     </tr>
    <tr>
        <td><?= $form->label($model, 'block_body') ?></td>
        <td><?= $form->checkBox($model, 'block_body', array('class' => 'blockInput', 'disabled' => 'disabled')) ?></td>
        <td><?= $form->label($model, 'attack_body') ?></td>
        <td><?= $form->checkBox($model, 'attack_body', array('class' => 'attackInput', 'disabled' => 'disabled')) ?></td>
    </tr>
    <tr>
        <td><?= $form->label($model, 'block_foot') ?></td>
        <td><?= $form->checkBox($model, 'block_foot', array('class' => 'blockInput', 'disabled' => 'disabled')) ?></td>
        <td><?= $form->label($model, 'attack_foot') ?></td>
        <td><?= $form->checkBox($model, 'attack_foot', array('class' => 'attackInput', 'disabled' => 'disabled')) ?></td>
    </tr>
    </table>
<?php $this->endWidget(); ?>
 </div>
<?= $this->renderPartial('_log', array('playerLog' => $model->log, 'enemyLog' => $enemyLog), true) ?>