<?php /** @var $this loginForm */ ?>
<?php
  $assets = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.components.widgets.assets'));
  Yii::app()->clientScript->registerScriptFile($assets.'/loginForm/login.js');
?>

<div class="form-play <?= $this->visible?"":"hidden" ?>">
  <div class="form-body">
    <span class="close-form"></span>
    <div class="row form-body__top fp-tabs-links">
      <div class="col-xs-15"><a id="fp-tab-register-link" data-href="#fp-tab-register" class="active"><?= t::get('Зарегистрироваться'); ?></a></div>
      <div class="col-xs-9"><a id="fp-tab-login-link" data-href="#fp-tab-login"><?= t::get('Войти в игру'); ?></a></div>
    </div>

    <div class="fp-tabs">
      <div id="fp-tab-register" >
        <?php
        foreach(Yii::app()->user->getFlashes() as $key => $message) {
          if ($key == 'login_ error'){
            echo '<div class="error flash-' . $key . '">' . $message . "</div>\n";
          }
        }

        ?>
        <form class="form-body__middle" autocomplete="off">
          <div class="row">
            <label for="login">
              <input id="login" autocomplete="off" type="text" value="" name='Players[user]' class="input-form ifc" placeholder="<?= t::get('Логин'); ?>">
             </label>
          </div>
          <div class="row sex-input">
            <label class="dropdown">
              <a href="#" class="dropdown-toggle input-form" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span><?= t::get('Пол персонажа'); ?></span> <span class="caret"></span></a>
              <ul class="dropdown-menu gender-select" aria-labelledby="dropdownMenu1">
                <li><a href="#" data-value="<?= Players::GENDER_MALE ?>"><?= t::get('Мужской'); ?></a></li>
                <li><a href="#" data-value="<?= Players::GENDER_FEMALE ?>"><?= t::get('Женский') ?></a></li>
              </ul>
              <input type="hidden" name="Players[gender]" class="gender-input" value="">
            </label>
          </div>
          <div class="row">
            <label for="email">
              <input id="email" type="text" value="" name="Players[email]" class="input-form ifc" placeholder="E-mail">
            </label>
          </div>
          <div class="row">
            <label for="password">
              <input id="password" type="password" value="" autocomplete="off" name="Players[pass]" class="input-form ifc" placeholder="<?= t::get('Пароль'); ?>">
            </label>
          </div>
          <div class="error"></div>
          <button type="submit" class="btn btn-go-play"><?= t::get('Начать игру'); ?></button>
          <p style="display: none;" class="preloader"><img src="//res.cloudinary.com/revival/image/upload/v1478265186/process_preloader_bbivmy.gif" alt="<?= t::get('Загрузка...') ?> | Revival Online"/></p>
        </form>
      </div>
      <div id="fp-tab-login" class="hidden">
        <form class="form-body__middle">
          <div class="row">
            <label for="login"><input id="login" type="text" autocomplete="off" value="" name='Players[user]' class="input-form ifc" placeholder="<?= t::get('Логин'); ?>"></label>
          </div>
          <div class="row">
            <label for="password">
              <input id="password" type="password" value="" autocomplete="off" class="input-form ifc" name='Players[pass]'  placeholder="<?= t::get('Пароль'); ?>">
            </label>
          </div>
          <div class="row">

              <div class="remember-checkbox">
                  <input id="remember" type="checkbox" name="Players[remember]" hidden /> <label for="remember"><?= t::get('Запомнить'); ?></label>
              </div>

<!--            <label for="remember">-->
<!--              <input    name='Players[remember]'>-->
<!--              -->
<!--            </label>-->
          </div>
          <div class="error"></div>
          <button type="submit" class="btn btn-go-play"><?= t::get('Войти'); ?></button>
        </form>
      </div>
    </div>

    <div class="social-auth form-body__bot">
      <span><?= t::get('Войти через соц. сети'); ?></span>
      <div class="row">
        <?php $this->widget('application.components.widgets.socialLogin') ?>
        <?php /*
        <script src="//ulogin.ru/js/ulogin.js"></script>
        <div id="uLogin_a344ee27" data-uloginid="a344ee27" data-lang="<?= t::iso(); ?>"></div>
        */ ?>
      </div>
      <a href="/page/rules" class="rulers-game"><?= t::get('Правила игры'); ?></a>
    </div>

  </div>
</div>

