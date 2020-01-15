<?php
  /** @var $reasons BannedPlayers[] */
  $reason = current($reasons);
?>

<h1><?= t::get('Ваш персонаж забанен') ?></h1>
<p>
  <?php
    if ($reason->until) {
      echo(t::get("Ваш персонаж забанен в игре до %s.<br> Если вы не знаете причины, обратитесь к администраторам игры Admin или Dobriy на форуме игры <a href='http://forum.rev-online.biz'>forum.rev-online.biz</a> или по ICQ 734-1111", date("d.m.Y", strtotime($reason->until))));
    } else {
      echo(t::get("Ваш персонаж забанен в игре навсегда!<br> Если вы не знаете причины, обратитесь к администраторам игры Admin или Dobriy на форуме игры <a href='http://forum.rev-online.biz'>forum.rev-online.biz</a> или по ICQ 734-1111"));
    }
  ?>
</p>

<?php

  if ($reason->comment) {
    echo("<p>".t::get('Комментарий: ')."{$reason->comment}</p>");
  }

?>

