<?
/**
 * @var $this Controller
 * @var $model CombatRound
 * @var $form CActiveForm
 */
?>
<div class="round">
    <table class="tab" width="100%">
        <tr>
            <td align="left"><?= t::get('Раунд %s:', $number) ?></td>
        </tr>
        <tr>
            <td align="center">
                <? foreach($round as $r): ?>
                    <?= $r . '<br />' ?>
                <? endforeach ?>
            </td>
        </tr>
    </table>
</div>