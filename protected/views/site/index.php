<?php
$formatter = new IntlDateFormatter(t::iso(), IntlDateFormatter::FULL, IntlDateFormatter::FULL);
$formatter->setPattern('d MMMM');
?>
    <section class="slider-wrapper-news">
      <h3><?= t::get('Последние новости') ?></h3>
      <div class="slider-news-body row" data-js-sly="data-js-sly">

        <div class="frame" id="cyclepages" data-js-sly-content="data-js-sly-content">
          <ul class="slidee">
            <?php
              foreach($news as $one) { ?>
              <?php /** @var $one News */ ?>
              <li><a  href="/new/<?php echo $one->id; ?>" ><img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" onload="lzld(this)" data-src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 380, 250); ?>" style='width:380px;height:250px;' class="news-item-img" alt="<?= $one->getML('title'); ?> | Revival Online"><div class="description-slide"></a>
                <span class="description-slide__date"><?php echo $formatter->format(new DateTime($one->date)); ?></span>
                <a href="/new/<?php echo $one->id; ?>" class="description-slide__title"><?php echo $one->getML('title'); ?></a>
              </li>
            <?php } ?>
          </ul>
        </div>
        <button class="latest-news-slider__arrow latest-news-slider__arrow_prev prevPage"></button>
        <button class="latest-news-slider__arrow latest-news-slider__arrow_next nextPage"></button>

        <div class="scrollbar">
          <div class="handle"></div>
        </div>
      </div>
    </section>

    <section class="about-text ">
      <div>
        <h2><?= t::get('Уникальный постапокалиптический мир') ?></h2>
        <span><?= t::get('Revival Online - уникальный мир, созданный по мотивам игры Fallout. Отсутствие клиента, облегченный интерфейс, разветвленная система крафтинга и квестов, неповторимый мир постапокалипсиса - все то, что делает игру популярной в Рунете.') ?></span>
        <p>
          <?= t::get('Огромное количество игроков в разных странах мира уже оценили Revival Online по достоинству. Проект в каком-то смысле превратился в социальную сеть!') ?>
          <?= t::get('Зарегистрируйтесь и вы сможете присоединиться к этой теплой компании и найти себе друзей среди тех, кто пытается выжить в мире после ядерного взрыва.') ?>
        </p>
      </div>
    </section>
    <div class="clearfix"></div>

    <section class="top-list row">
      <div class="col-xs-10 top-list__lc">
        <span class="top-box-title">><?= t::get('Топовые кланы') ?></span>
        <ul class="top-box-list">
          <?php $i=1;
          foreach($clans as $one) { ?>
            <li class="row"><span class="count-places col-xs-2"><?php echo $i; ?>.</span><span class="name-list col-xs-22"><?php echo $one->name; ?></span></li>
            <?php $i++; } ?>
        </ul>
      </div>
      <div class="col-xs-14 top-list__rc">
        <span class="top-box-title">><?= t::get('Топовые игроки') ?></span>
        <ul class="top-box-list">
          <?php $i=1;
          foreach($players as $one) { ?>
            <li class="row"><span class="count-places col-xs-2"><?php echo $i; ?>.</span><span class="name-item col-xs-11"><?php echo $one->user; ?></span><span class="lvl-item col-xs-5"><?php echo $one->level; ?> lvl,</span><span class="exp-item col-xs-6"><?php echo $one->exp; ?> exp</span></li>
            <?php $i++; } ?>
        </ul>
      </div>
    </section>

    <section class="section-video-slider">
      <h3><?= t::get('Видео и скриншоты') ?></h3>
      <?php
      $this->widget('application.components.widgets.PhotoVideo', array(
        'layout'  => 'home'
      ));
      ?>
    </section>


