<?php
  /** @var $content string */
  /** @var $this Controller */
?><!DOCTYPE html>
<html lang="<?= t::iso(); ?>">
  <head>
    <meta charset="utf-8" />
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title><?= $this->pageTitle ?></title>
    <meta name="viewport" content="width=1247">
    <meta name="description" content="<?= isset($this->metaDescription)?$this->metaDescription:'' ?>" />
    <meta name="google-site-verification" content="vnjv385lELYwP0uED4f0PIQlgmA2I_vNoK46UFrANjQ" />
    <?php $image = '/img/social/'.t::iso().'/'.mt_rand(1,2).'.png'; ?>
    <meta property="og:image" content="<?= Yii::app()->createAbsoluteUrl($image); ?>" />
    <?php if (t::iso() == 'ru') { ?>
      <meta name="yandex-verification" content="223581fb69d17ecb" />
    <?php } ?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
    <base href="<?= Yii::app()->request->getBaseUrl(true) ?>" />
    <?php if(isset($this->canonical_url) && $this->canonical_url!='') {
      echo "<link rel='canonical' href='".$this->canonical_url."' />";
    } ?>
  </head>

  <div id="outer-wrap">
    <div id="video-wrap" class="video-wrap">
      <video preload="metadata" autoplay="" loop="" id="my-video" muted>
        <source src="/movie.mp4" type="video/mp4">
      </video>
    </div>
  </div>

<body class="<?= isset($this->bodyClass)?$this->bodyClass:'' ?> <?= Yii::app()->stat->model?"player":"guest" ?>" data-uid="<?= (int)Yii::app()->stat->id ?>">

<div class="wrapper">
  <header class="header">
    <div class="header-wrapper row">
      <div class="logo">
        <a href="/"><img src="//res.cloudinary.com/revival/image/upload/v1478264264/revlogo_z2nw7r.png" alt="Revival Online"></a>
      </div>
      <div class="header-nav header-nav-left">
        <a class="burger-button">
          <span></span>
          <span></span>
          <span></span>
        </a>
        <ul class="row header-main-nav">
          <li><a href="/page/about" <?= (isset($this->active_page) && $this->active_page=='/page/about')?'class="active"':''; ?>><?= t::get('О чем игра?'); ?></a></li>
          <li><a href="/new" <?= (isset($this->active_page) && $this->active_page=='/new')?'class="active"':''; ?>><?= t::get('Новости'); ?></a></li>
          <li><a href="/article" <?= (isset($this->active_page) && $this->active_page=='/article')?'class="active"':''; ?>><?= t::get('О серии игр Fallout') ?></a></li>
          <li><a href="/faq" <?= (isset($this->active_page) && $this->active_page=='/faq')?'class="active"':''; ?>><?= t::get('FAQ'); ?></a></li>
          <li><a href="/newuser" <?= (isset($this->active_page) && $this->active_page=='/newuser')?'class="active"':''; ?>><?= t::get('Новичкам') ?></a></li>
          <li><a href="<?= t::get_forum_link() ?>"><?= t::get('Community') ?></a></li>
        </ul>
      </div>
      <div class="header-nav header-nav-left">
        <ul class="row social-icons">
          <li><a class="social-icon social-icon-fb" target="_blank" href="https://www.facebook.com/revivalon/"></a></li>
          <li><a class="social-icon social-icon-vk" target="_blank" href="https://vk.com/revivalonline"></a></li>
          <!-- <li><a class="social-icon social-icon-gp" target="_blank" href="#"></a></li> -->
          <li><a class="social-icon social-icon-tw" target="_blank" href="https://twitter.com/Revival_on"></a></li>
        </ul>
      </div>
      <div class="header-nav header-nav-right">
        <ul class="row">
          <li class="language-list dropdown"><?php t::widget('home') ?></li>
          <?php if (Yii::app()->stat->model ){ ?>
            <li class="register-link"><a href='/city.php'><?= t::get('Игра') ?></a></li>
            <li class="register-login"><a href='/logout'><?= t::get('ВЫХОД') ?></a></li>
          <?php } else {?>
            <li class="register-link"><a href='#' class="switch-tab" data-href="#fp-tab-register"><?= t::get('Регистрация') ?></a></li>
            <li class="register-login"><a href='#' class="switch-tab" data-href="#fp-tab-login"><?= t::get('ВХОД'); ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <?php
    $this->widget('application.components.widgets.loginForm', array(
      'layout'  => 'home',
      'visible' => isset($this->loginForm)?$this->loginForm:false
    ));
    ?>
  </header>
  <div class="content-wrapper">
    <main class="content">
      <div class="content-wrapper-inner">
        <?php echo $content; ?>
        <?php if (!Yii::app()->stat->model) { ?>
          <section class="section-start-game text-center">
            <span class="start-game__title"><?= t::get('Каким героем станеш ты?') ?></span>
            <p class="start-game__text"><?= t::get('Беспощадным наемником-уничтожителем или добродушным отшельником скитальцем?')?></p>
            <button class="start-game__btn switch-tab" data-href="#fp-tab-register"><?= t::get('Начать игру') ?></button>
          </section>
        <?php } ?>
      </div>
    </main>
  </div>
</div>

<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 copyright text-left">
        <p class="copyright__text"><?= t::get('(c) Revival online — browser online strategy 2009-2016') ?></p>
      </div>
      <div class="col-xs-6 header-nav header-nav-left">
        <ul class="row social-icons">
          <li><a class="social-icon social-icon-fb" target="_blank" href="https://www.facebook.com/revivalon/"></a></li>
          <li><a class="social-icon social-icon-vk" target="_blank" href="https://vk.com/revivalonline"></a></li>
          <!--<li><a class="social-icon social-icon-gp" target="_blank" href="#"></a></li> -->
          <li><a class="social-icon social-icon-tw" target="_blank" href="https://twitter.com/Revival_on"></a></li>
        </ul>
      </div>
      <div class="col-xs-6 privacy-policy text-right">
        <a href="/page/policy" class="privacy-policy__link"><?= t::get('Политика конфиденциальности') ?></a>
      </div>
    </div>
  </div>
</footer>

<?php
Yii::app()->clientScript->registerScriptFile('/js/dklab_realplexor.js', CClientScript::POS_END);
?>
</body>
</html>