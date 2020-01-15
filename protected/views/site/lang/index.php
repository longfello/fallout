<?php
  /** @var $lang t */
?>

<ul class="language-chooser">
  <?php foreach($lang->languages as $language){ ?>
    <li class="lang-<?= ($language->id == $lang->language->id)?'active':'passive' ?> lang-iso-<?= $language->slug ?>" title="<?= $language->name ?>">
      <?php if ($language->id == $lang->language->id) { ?>
        <?= $language->slug ?>
      <?php } else { ?>
        <a href="<?= t::getInstance()->getLocaleDomain($language->slug) ?>"><?= $language->slug ?></a>
      <?php } ?>
    </li>
  <?php } ?>
</ul>
