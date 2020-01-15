<?
/**
 * @var $this Controller
 * @var $model CombatRound
 * @var $form CActiveForm
 */
?>
<div id="attackFormBlock">
    <? $form = $this->beginWidget('CActiveForm', array(
        'id' => 'attackForm',
        'htmlOptions' => array('class' => 'attackForm', 'autocomplete' => 'off')
    )) ?>
    <table class="tab">
        <tr>
            <td colspan="4" align="center"><?= t::get('Ваш ход') ?></td>
        </tr>
        <tr>
            <td colspan="2"><?= t::get('Куда ставить блок:') ?></td>
            <td colspan="2"><?= t::get('Куда бить:') ?></td>
        </tr>
        <tr>
            <td><?= $form->label($model, 'block_head') ?></td>
            <td><?= $form->checkBox($model, 'block_head', array('class' => 'blockInput')) ?></td>
            <td><?= $form->label($model, 'attack_head') ?></td>
            <td><?= $form->checkBox($model, 'attack_head', array('class' => 'attackInput')) ?></td>
        </tr>
        <tr>
            <td><?= $form->label($model, 'block_hand') ?></td>
            <td><?= $form->checkBox($model, 'block_hand', array('class' => 'blockInput')) ?></td>
            <td><?= $form->label($model, 'attack_hand') ?></td>
            <td><?= $form->checkBox($model, 'attack_hand', array('class' => 'attackInput')) ?></td>
        </tr>
        <tr>
            <td><?= $form->label($model, 'block_body') ?></td>
            <td><?= $form->checkBox($model, 'block_body', array('class' => 'blockInput')) ?></td>
            <td><?= $form->label($model, 'attack_body') ?></td>
            <td><?= $form->checkBox($model, 'attack_body', array('class' => 'attackInput')) ?></td>
        </tr>
        <tr>
            <td><?= $form->label($model, 'block_foot') ?></td>
            <td><?= $form->checkBox($model, 'block_foot', array('class' => 'blockInput')) ?></td>
            <td><?= $form->label($model, 'attack_foot') ?></td>
            <td><?= $form->checkBox($model, 'attack_foot', array('class' => 'attackInput')) ?></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><button type="submit"><?= t::get('В бой') ?></button></td>
        </tr>
    </table>
    <?php $this->endWidget(); ?>
</div>