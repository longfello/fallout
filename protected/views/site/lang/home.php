<?php
/** @var $lang t */
?>
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= $lang->language->slug; ?><span class="caret"></span></a>
<ul class="dropdown-menu">
  <?php foreach($lang->languages as $language){ ?>
    <li class="lang-<?= ($language->id == $lang->language->id)?'active':'passive' ?> lang-iso-<?= $language->slug ?>" title="<?= $language->name ?>">
      <a href="<?= t::getInstance()->getLocaleDomain($language->slug) ?>" title="<?= $language->name ?>"><?= $language->slug ?></a>
    </li>
  <?php } ?>
</ul>
