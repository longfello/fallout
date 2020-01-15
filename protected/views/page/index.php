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
    <div class="body-page row">
      <div class="about-page__wrapper">
        <div class="a-text-out row">
          <div class="about-text ">
            <div>
              <h2 class="text-uppercase"><?= t::get('Уникальный постапокалиптический мир') ?></h2>
              <p><?= t::get('Revival Online - уникальный мир, созданный по мотивам игры Fallout. Отсутствие клиента, облегченный интерфейс, разветвленная система крафтинга и квестов, неповторимый мир постапокалипсиса - все то, что делает игру популярной в Рунете.') ?></p>
              <p><?= t::get('Огромное количество игроков в разных странах мира уже оценили Revival Online по достоинству. Проект в каком-то смысле превратился в социальную сеть!') ?>
              <?= t::get('Зарегистрируйтесь и вы сможете присоединиться к этой теплой компании и найти себе друзей среди тех, кто пытается выжить в мире после ядерного взрыва.') ?></p>
            </div>
          </div>
        </div>
        <div class="row first-block-wrapper">
          <?php echo $post->content; ?>
        </div>
      </div>
      <div class="row">
        <?php foreach($news as $one) { ?>
          <div class="col-xs-6 post-item row">
            <div class="post-item__inner">
              <div class="post-item__img">
                <a href="/new/<?php echo $one->id; ?>" class="post-item__img-link"> <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 380, 250); ?>" alt="<?= $one->getML('title'); ?> | Revival Online"> </a>
              </div>
              <div class="post-item__title">
                <a href="/new/<?php echo $one->id; ?>" class="post-item__title-link"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></a>
              </div> <div class="post-item__date"><?php echo $formatter->format(new DateTime($one->date)); ?></div>
              <div class="post-item__text"><?= RText::truncateTextCount($one->getML('news'), 80, '...') ?></div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>

  </section>