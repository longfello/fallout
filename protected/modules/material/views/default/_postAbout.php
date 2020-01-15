<? $this->pageTitle = $post->title ?>
<? $this->metaDescription = t::get("Бесплатная пошаговая мморпг(mmorpg) онлайн игра. Приглашаем всех в мир самой лучшей русской mmorpg(мморпг) игры онлайн — Rev-online") ?>

<?= t::get('material-about-text') ?>

<div class="date"><? if ($post->date) echo date('d.m.Y', strtotime($post->date)); ?></div>
<div class="clear"></div>
<?= $post->title_news ?>
<!--<p class="newsContent"><?= $post->news ?></p>-->