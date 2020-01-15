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
                <td align="left">Раунд <?= $model->round ?>:</td>
            </tr>
            <tr>
                <td align="center">
                    <?= $this->renderPartial('_log', array('playerLog' => $model->log, 'enemyLog' => $enemyLog), true) ?>
                </td>
            </tr>
        </table>
    </div>