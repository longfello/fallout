<?php
/** @var $lang t */
?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo ucfirst(t::iso()); ?> <span class="caret"></span></a>
<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
  <?php foreach($lang->languages as $language){ ?>
    <li class="lang-<?= ($language->id == $lang->language->id)?'active':'passive' ?> lang-iso-<?= $language->slug ?>" title="<?= $language->name ?>">
      <a href="<?= t::getInstance()->getLocaleDomain($language->slug) ?>"><?= $language->name ?></a>
    </li>
  <?php } ?>
</ul>
