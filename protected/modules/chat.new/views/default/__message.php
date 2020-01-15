<?php
/**
 * @var $this DefaultController
 * @var $message array
 */

?>


<li class="message">

	<div class="chat chat-<?= $message['id'] ?>" data-chat-id="<?= $message['id'] ?>">
		<?php if (in_array(Yii::app()->stat->rank, array('Админ', 'Модер', 'Чат-Модер'))) { ?>
			<?php if ($message['id']){ ?>
				<span class="deleteMsg" title="<?= t::encHtml( 'Удалить') ?>">&times;</span>
			<?php } else { ?>
			<span class="nullMsg">&nbsp;</span>
			<?php } ?>
		<?php } ?>

		<span class="time"><?= $message['time'] ?></span>
		<span class="nick <?= $message['style'] ?> u<?= $message['user_id'] ?> " data-user-id="<?= $message['user_id'] ?>"><?= $message['user'] ?></span>
		<?php if ($message['private']) { ?>
			<span class="privateInfo">
				[ <?= t::get( 'лично для' ) ?> <span class="nick <?= $message['to_style'] ?> u<?= $message['to_player'] ?> " data-user-id="<?= $message['to_player'] ?>"><?= $message['private_user'] ?></span> ]
			</span>
		<?php } ?>
		&raquo;&raquo; <span class="text"><?= $message['message'] ?></span>
	</div>
</li>


