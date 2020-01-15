<?
/**
 * @var $this Controller
 */
Yii::app()->getModule('combat');
Combat::checkAttack();
?>
<?= CHtml::image('/images/sulfur-mine.jpg', '', array('id' => 'inner_image_bg'))?>
<? if (Yii::app()->user->hasFlash('error')): ?>
    <p><?= Window::error(Yii::app()->user->getFlash('error')) ?></p>
<? endif ?>
<p>
  <?= t::get('mine-title-text', array(CHtml::link(t::get('местном рынке'), '/imarket_caves.php'))) ?>
</p><br />
<?= CHtml::link(t::get('Начать работу на Серном руднике'), $this->createUrl('offer'), ['class' => 'ajaxify']) ?><br />
<?= CHtml::link(t::get('Увеличить время работы на руднике'), $this->createUrl('mineshop/index')) ?><br />
<?= CHtml::link(t::get('Выйти в главный зал пещер'), '/caves.php') ?><br />