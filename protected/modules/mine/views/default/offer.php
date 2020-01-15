<?
/**
 * @var $this Controller
 * @var $messages SulfurMineMessage[]
 */
Yii::app()->getModule('combat');
Combat::checkAttack();
?>
<?= CHtml::image('/images/sulfur-mine.jpg', '', array('id' => 'inner_image_bg'))?>
<? if (Yii::app()->user->hasFlash('error')): ?>
    <p><?= Window::error(Yii::app()->user->getFlash('error')) ?></p>
<? endif ?>
<? if (Yii::app()->user->hasFlash('completed')): ?>
    <p><?= Yii::app()->user->getFlash('completed') ?></p>
<? else: ?>
<p><?= t::get('Вы начали работу в серном руднике. Выберите действие.') ?></p>
<? endif ?>
<br />
<ul>
<? foreach ($messages as $message): ?>
    <li><?= CHtml::link($message->message, $this->createUrl('work', array('id' => $message->message_id)), ['class' => 'ajaxify'])?></li>
<? endforeach ?>
</ul>
<br />
<p><?= t::get('Оставшееся количество действий на руднике:') ?> <?= $count ?></p>