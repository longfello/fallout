<?
/**
 * @var $items[]
 * @var $this Controller
 */
?>
<script type="text/javascript" src="/js/components/food.js"></script>


<?
  $msg  = t::get('Владелец магазина: Стим<hr>Добро пожаловать, <b>%s</b>, в мой скромный магазин.<br> Оплата за продукты и препараты производится водой, которую вы можете добыть в частных домах или купить у торговца за крышечки.', array(Yii::app()->stat->user));
  $msg .= t::get('Почему именно вода в обмен на товар? Просто весь товар из других городов несут сюда менять исключительно на чистую воду. <br><br> <center>У вас: %s<img src="/images/tokens.png" alt="Вода"> воды</center>', array(Yii::app()->stat->tokens));
  Tool::talkingHead('watershop', $msg, '', 500);
?>
<? if (Yii::app()->user->hasFlash('buyError')): ?>
   <?= Window::error(Yii::app()->user->getFlash('buyError')) ?>
<? endif ?>
<? if (Yii::app()->user->hasFlash('buySuccess')): ?>
    <?= Window::highlight(Yii::app()->user->getFlash('buySuccess')) ?>
<? endif ?>
<table class="tab tabInfo" width="100%" cellspacing="3" cellpadding="3">
    <? foreach ($items as $item): ?>
    <?php
      $item->name = t::getDb('name', 'equipment', 'id', $item->id);
      $item->opis = t::getDb('opis', 'equipment', 'id', $item->id);
      $equipment = Equipment::model()->findByPk($item->id);

        ?>
    <tr>
        <td width="20%" align="center" onmouseout="c();" onmouseover="hint(document.getElementById('<?=$item->id?>').innerHTML, event);">
            <span id="<?=$item->id?>" style="display:none;"><?= Item::getPopupContent($item) ?></span><?= CHtml::image($equipment->getPicture(Equipment::IMAGE_SMALL)) ?>
        </td>
        <td width="40%"><?= Item::getTypeName($item->type) ?>:<br /><br /><?= $item->name?></td>
        <td>
            <?= CHtml::beginForm(
                $this->createUrl('buy')
            ) ?>
            <?= CHtml::hiddenField('id', $item->id) ?>
            <? if (Item::isAvailable($item)): ?>
                <?= CHtml::textField('count', 1, array('size' => '2')) ?>
                <?= CHtml::submitButton('submit', array('value' => t::get('купить'))) ?>
            <? else: ?>
                <?= CHtml::textField('count', 1, array('size' => '2', 'class' => 'deny')) ?>
                <?= CHtml::submitButton('submit', array('value' => t::get('купить'), 'class' => 'deny')) ?>
            <? endif ?>
            <?= CHtml::endForm() ?>
        </td>
    </tr>
    <? endforeach ?>
</table>