<?php
  /**
   *
   */
?>
<h3><?= t::get('Адрес электронной почты не подтвержден.') ?></h3>
<p><?= t::get('Для подтверждения адреса электронной почты введите код из письма:') ?></p>
	<form action="/player/activation" method="post">
		<input type="hidden" name="<?= Yii::app()->request->csrfTokenName?>" value="<?= Yii::app()->request->csrfToken ?>">
		<input type="text" name="code" placeholder="<?= t::get('Код указанный в письме') ?>">
		<button type="submit" class="btn btn-primary"><?= t::get('Отправить'); ?></button>
	</form>
<p><?= t::get('или') ?></p>
<form action="/player/sendActivation" method="post">
	<input type="hidden" name="<?= Yii::app()->request->csrfTokenName?>" value="<?= Yii::app()->request->csrfToken ?>">
	<input type="text" name="email" placeholder="<?= t::get('Адрес электронной почты') ?>" value="<?= $email ?>">
	<button type="submit" class="btn btn-primary"><?= t::get('Отправить письмо с кодом'); ?></button>
</form>
