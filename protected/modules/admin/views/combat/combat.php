<?php
/**
 * @var $this DefaultController
 * @var $combat Combat
 */
?>
<div class="combat" id="combat">
    <div class="combat-main">
        <div class="fight-log">
          <?php
            $logs = $combat->getLog();
            foreach($logs as $round) {
              ?>
              <div class="round">
                <?php foreach($round as $text) { ?>
                  <p><?= $text ?></p>
                <?php } ?>
              </div>
              <?php
            }
          ?>
        </div>
    </div>
</div>