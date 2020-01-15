<?php
$formatter = new IntlDateFormatter(t::iso(), IntlDateFormatter::FULL, IntlDateFormatter::FULL);
$formatter->setPattern('d MMMM');
?>
<div class="form-play-go">
  <div class="form-body">
    <div class="form-body__middle">
      <button class="switch-tab btn btn-go-play" data-href="#fp-tab-register"><?= t::get('Начать игру'); ?></button>
    </div>
  </div>
</div>

<section class="wrapper-page">
  <h3 class="title-page text-uppercase"><?= t::get('О СЕРИИ ИГР fallout'); ?></h3>

  <div class="body-page row">
    <?php foreach($news as $one) { ?>
      <div class="col-xs-6 post-item row">
        <div class="post-item__inner">
          <div class="post-item__img">
            <a href="/article/<?php echo $one->id; ?>" class="post-item__img-link"> <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 218, 140); ?>" alt="<?= addslashes($one->getML('title')); ?> | Revival Online"> </a>
          </div>
          <div class="post-item__title">
            <a href="/article/<?php echo $one->id; ?>" class="post-item__title-link"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></a>
          </div> <div class="post-item__date"><?php echo $formatter->format(new DateTime($one->date)); ?></div>
          <div class="post-item__text"><?= RText::truncateTextCount($one->getML('news'), 80, '...') ?></div>
        </div>
      </div>
    <?php } ?>
  </div>

  <div class="pagination">
    <?php $this->widget('application.components.widgets.HomePager', array(
      'pages'=>$pagination,
    ));
    ?>
  </div>

</section>


