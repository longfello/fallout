<?php if ($event['date']) { ?>
<li class="time-label">
  <span class="bg-red"><?= $event['date'] ?></span>
</li>
<?php } ?>
<li>
  <?= $event['icon']?>

  <div class="timeline-item">
    <span class="time"><i class="fa fa-clock-o"></i><?= $event['time'] ?></span>

    <h3 class="timeline-header"><?= $event['title']?$event['title']:'&nbsp;' ?></h3>

    <div class="timeline-body">
      <?= $event['description'] ?>
    </div>
    <div class="timeline-footer">
      <?= $event['footer'] ?>
    </div>
  </div>
</li>