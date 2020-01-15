<?php
/* @var $this CombatController */
/* @var $player1 Players */
/* @var $player2 Players */
?>

<div class="row combat-parameters">
	<div class="col-xs-6">
		<?= $this->renderPartial('__player_editor', array('model' => $player1)) ?>
	</div>
	<div class="col-xs-6">
		<?= $this->renderPartial('__player_editor', array('model' => $player2)) ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="well">
			<button class="btn btn-primary btn-lg btn-block btnStartCombat">
				<i class="fa fa-wheelchair"></i>
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw hidden"></i>
				<span>Старт боя</span>
			</button>
			<div class="combat-log"></div>
		</div>
	</div>
</div>

<?php

?>