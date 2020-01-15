<?php
/* @var $this SiteController */
/* @var $error array */

$this->breadcrumbs=array(
	'Error',
);

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


  <?php if (!Yii::app()->user->isGuest){ ?>
  <div class="error">
    <h2>Error <?php echo $error['code']; ?></h2>
  <pre>

<?php echo $error['message']; ?>

in file <?= $error['file'] ?> [ line <?=$error['line']?> ]

<?= $error['trace'] ?>
</pre>
    <br><br><br><br>
  </div>
  <?php } else {?>
    <div class="error-guest">
      <h2><?= $error['code']; ?></h2>
      <?php if ($error['code']==404) { ?>
        <h3><?= t::get('Страница не найдена') ?></h3>
      <?php } ?>
    </div>
  <?php } ?>


  <div class="row">
    <h4><?= t::get('Как насчет этих?'); ?></h4>
    <?php foreach($articles as $one) { ?>
      <div class="col-xs-6 post-item row">
        <div class="post-item__inner">
          <div class="post-item__img">
            <a href="/article/<?php echo $one->id; ?>" class="post-item__img-link"> <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 380, 250); ?>" alt=""> </a>
          </div>
          <div class="post-item__title">
            <a href="/article/<?php echo $one->id; ?>" class="post-item__title-link"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></a>
          </div> <div class="post-item__date"><?php echo $formatter->format(new DateTime($one->date)); ?></div>
          <div class="post-item__text"><?= RText::truncateTextCount($one->getML('news'), 80, '...') ?></div>
        </div>
      </div>
    <?php }  ?>
  </div>
</section>