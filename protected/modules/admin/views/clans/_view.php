<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
  <br/>

  <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
  <?php echo CHtml::encode($data->name); ?>
  <br/>

  <b><?php echo CHtml::encode($data->getAttributeLabel('owner')); ?>:</b>
  <?php echo CHtml::encode($data->owner); ?>
  <br/>

  <b><?php echo CHtml::encode($data->getAttributeLabel('coowner')); ?>:</b>
  <?php echo CHtml::encode($data->coowner); ?>
  <br/>

  <b><?php echo CHtml::encode($data->getAttributeLabel('gold')); ?>:</b>
  <?php echo CHtml::encode($data->gold); ?>
  <br/>

  <b><?php echo CHtml::encode($data->getAttributeLabel('platinum')); ?>:</b>
  <?php echo CHtml::encode($data->platinum); ?>
  <br/>

  <b><?php echo CHtml::encode($data->getAttributeLabel('public_msg')); ?>:</b>
  <?php echo CHtml::encode($data->public_msg); ?>
  <br/>

  <?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('private_msg')); ?>:</b>
	<?php echo CHtml::encode($data->private_msg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag')); ?>:</b>
	<?php echo CHtml::encode($data->tag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pass')); ?>:</b>
	<?php echo CHtml::encode($data->pass); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('moneypass')); ?>:</b>
	<?php echo CHtml::encode($data->moneypass); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hospass')); ?>:</b>
	<?php echo CHtml::encode($data->hospass); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clanwars')); ?>:</b>
	<?php echo CHtml::encode($data->clanwars); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('chatroom')); ?>:</b>
	<?php echo CHtml::encode($data->chatroom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clanstore')); ?>:</b>
	<?php echo CHtml::encode($data->clanstore); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('clan_tax_ranges')); ?>:</b>
	<?php echo CHtml::encode($data->clan_tax_ranges); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('war_win')); ?>:</b>
	<?php echo CHtml::encode($data->war_win); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('union_win')); ?>:</b>
	<?php echo CHtml::encode($data->union_win); ?>
	<br />

	*/ ?>

</div>