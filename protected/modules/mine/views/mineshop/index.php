<?
/**
 * @var $this Controller
 */
?>
<?= CHtml::image('/images/sulfur-mine.jpg', '', array('id' => 'inner_image_bg'))?>
<? if (Yii::app()->user->hasFlash('error')): ?>
    <p><?= Window::error(Yii::app()->user->getFlash('error')) ?></p>
<? endif ?>
<? if (Yii::app()->user->hasFlash('success')): ?>
    <p><?= Window::highlight(Yii::app()->user->getFlash('success')) ?></p>
<? endif ?>
<br />
<p><?= t::get('Оставшееся количество действий на руднике:') ?> <?= $count ?></p><br />
<p><?= t::get('Ты можешь увеличить свою работоспособность и повысить сопротивление токсинам, если купишь специальную инъекцию. Она даст единоразово дополнительные 30 действий на серном руднике. Стоимость 20<img src="/images/platinum.png"> крышек.') ?></p>
<br />
<?= CHtml::link(t::get('Купить инъекцию за 20 крышек (+30 действий на серном руднике).'), $this->createUrl('buy'), array('onclick' => 'return dialog("'.t::encJs('Вы действительно хотите приобрести инъекцию за 20 крышек? Инъекция действует до респауна.').'", "' . $this->createUrl('buy') . '")')) ?><br />
<?= CHtml::link(t::get('Как-нибудь в другой раз'), $this->createUrl('/mine'), ['class' => 'ajaxify']) ?><br />
<?= CHtml::link(t::get('Выйти в главный зал рудника'), $this->createUrl('/mine'), ['class' => 'ajaxify']) ?><br />