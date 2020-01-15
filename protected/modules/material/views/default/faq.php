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
  <h3 class="title-page text-uppercase"><?= t::get('Частые вопросы и ответы на них'); ?></h3>
  <div class="body-page row">
    <div class="faq-page__wrapper">
      <div class="faq-list row">
        <div class="col-xs-8 faq-list__lc">
          <ul>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [1] | Revival Online" src="/images/faq-img_01.png"></div></li>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [2] | Revival Online" src="/images/faq-img_02.png"></div></li>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [3] | Revival Online" src="/images/faq-img_03.png"></div></li>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [4] | Revival Online" src="/images/faq-img_04.png"></div></li>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [5] | Revival Online" src="/images/faq-img_05.png"></div></li>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [6] | Revival Online" src="/images/faq-img_06.png"></div></li>
            <li><div class="faq-item__logo text-center"><img alt="<?= t::get('Частые вопросы и ответы на них'); ?> [7] | Revival Online" src="/images/faq-img_07.png"></div></li>
          </ul>
        </div>
        <div class="col-xs-16 faq-list__rc">
          <ul>
            <?php
              foreach($faqs as $faq) {
                /** @var $faq RNews */
                ?>
                  <li>
                    <span class="faq-page__question"><?= $faq->getML('title') ?></span>
                    <p class="faq-page__answer">
                      <span class="faq-page__answer-text"><?= t::get('Ответ') ?>:</span> <?= strip_tags($faq->getML('news'), "<br><a><span><img>") ?>
                    </p>
                  </li>
                <?php
              }
            ?>
          </ul>
        </div>
      </div>
      <div class="text-center have-questions">
        <span class="faq-page__question"><?= t::get('Остались вопросы?') ?></span>
        <p class="faq-page__answer"><?= t::get('Сейчас же задавайте их администраторам: Dobriy [ID 4] или Neoman [ID 1]!'); ?></p>
      </div>
    </div>
    <div class="row">
      <?php
        foreach($news as $one){
          ?>
            <div class="col-xs-6 post-item row">
              <div class="post-item__inner"> 
                <div class="post-item__img">
                  <a class="post-item__img-link" href="<?php echo $one->getURL(); ?>"><img alt="<?= $one->getML('title'); ?> | Revival Online" src="<?= Thumbler::getUrl('news/'.$one->img, 218, 140); ?>"></a>
                </div> 
                <div class="post-item__title"> 
                  <a class="post-item__title-link" href="<?php echo $one->getURL(); ?>"><?= mb_substr(strip_tags($one->getML('title')), 0, 50) ?></a>
                </div> 
                <div class="post-item__date"><?php echo $formatter->format(new DateTime($one->date)); ?></div>
                <div class="post-item__text">
                  <?= mb_substr(strip_tags($one->getML('news')), 0, 150) ?>
                </div> 
              </div> 
            </div>
          <?php
        }
      ?>
    </div>
  </div>
</section>
