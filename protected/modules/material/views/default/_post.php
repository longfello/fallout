<? $this->pageTitle = Yii::app()->name . ' - ' . $post->title; ?>
<? $this->metaDescription = $post->description ?>
<div class="date"><? if ($post->date) echo date('d.m.Y', strtotime($post->date)); ?></div>
<div class="clear"></div>
<h1><?= $post->title_news ?></h1>
<p class="newsContent"><?= $post->news ?></p>