<?php
/**
 * @var $this DefaultController
 * @var $player array
 */

?>


<li class="user">
	<?php /*	<span class="rankIcon"><?= $player['rankIcon'] ?></span> */ ?>
	<span data-user-id="<?= $player['id']?>" class="nick <?= $player['style'] ?> u<?= $player['id']?>"><?= $player['user']?></span>
	<span class="clanIcon">
		<div class="user-popup">
			<a target="_blank" href="/view.php?view=<?= $player['id']?>">
				<?= $player['avatar'] ?>
				<div class="user-id">[<?= $player['id']?>]</div>
			</a>
		</div>
		<?= $player['clanIcon'] ?>
	</span>
</li>


