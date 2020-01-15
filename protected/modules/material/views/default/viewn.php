<?php
$formatter = new IntlDateFormatter(t::iso(), IntlDateFormatter::FULL, IntlDateFormatter::FULL);
$formatter->setPattern('d MMMM Y');
?>
<div class="form-play-go">
  <div class="form-body">
    <div class="form-body__middle">
      <button class="switch-tab btn btn-go-play" data-href="#fp-tab-register"><?= t::get('Начать игру'); ?></button>
    </div>
  </div>
</div>
<section class="wrapper-page">
  <h3 class="title-page text-uppercase"><?php echo $post->title; ?></h3>

  <div class="body-page row">


    <div class="articles-inner">

      <?php echo $post->news; ?>

    </div>


    <div class="row">
      <?php foreach($news as $one) { ?>
        <div class="col-xs-6 post-item row">
          <div class="post-item__inner">
            <div class="post-item__img">
              <a href="<?php echo $one->getURL(); ?>" class="post-item__img-link"> <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 380, 250); ?>" alt=""> </a>
            </div>
            <div class="post-item__title">
              <a href="<?php echo $one->getURL(); ?>" class="post-item__title-link"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></a>
            </div> <div class="post-item__date"><?php echo $formatter->format(new DateTime($one->date)); ?></div>
            <div class="post-item__text"><?= RText::truncateTextCount($one->getML('news'), 80, '...') ?></div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

</section>
