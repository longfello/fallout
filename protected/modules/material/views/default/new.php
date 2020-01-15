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

  <div class="body-page body-page-news row">
    <ul>
      <?php $i=1; foreach($news as $one) { if ($i==1){ ?>
        <li class="col-xs-16">
          <div class="news-post-item">
            <a href="<?php echo $one->getURL(); ?>" class="horizontal">
              <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 722, 488); ?>" class="news-item-img" alt="<?= $one->getML('title'); ?> | Revival Online">
                  <span class="description-slide text-left">
                    <span class="description-slide__date"><?php echo $formatter->format(new DateTime($one->date)); ?></span>
                    <span class="description-slide__title"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></span>
                  </span>
            </a>
          </div>
        </li>
        <?php } else { ?>
        <li class="col-xs-8">
          <div class="news-post-item">
            <a href="<?php echo $one->getURL(); ?>" class="horizontal">
              <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 335, 217); ?>" class="news-item-img" alt="<?= $one->getML('title'); ?> | Revival Online">
                  <span class="description-slide text-left">
                    <span class="description-slide__date"><?php echo $formatter->format(new DateTime($one->date)); ?></span>
                    <span class="description-slide__title"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></span>
                  </span>
            </a>
          </div>
        </li>
      <?php } $i++; } ?>
    </ul>
  </div>

  <div class="pagination">
    <?php $this->widget('application.components.widgets.HomePager', array(
    'pages'=>$pagination,
    ));
    ?>
  </div>
</section>
