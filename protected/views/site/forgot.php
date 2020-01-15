<div class="forgot-wrapper">
<div class="form-play ">
    <div class="form-body">
        <div class="row form-body__top fp-tabs-links">
            <p class="forgot-text"><?= t::get('Для восстановления пароля заполните следующее поле') ?>:</p>
        </div>
        <div class="fp-tabs">
            <div id="fp-tab-forgot">
                <form autocomplete="off" class="form-body__middle">
                    <div class="row">
                        <label for="email">
                            <input type="text" placeholder="E-mail" class="input-form" name="Players[email]" value="" id="email">
                        </label>
                    </div>
                    <div class="error"></div>
                    <button class="btn btn-go-play" type="submit"><?= t::get('Восстановить') ?></button>
                    <p style="display: none;" class="preloader"><img src="//res.cloudinary.com/revival/image/upload/v1478265186/process_preloader_bbivmy.gif" alt="<?= t::get('Загрузка...') ?> | Revival Online" /></p>
                </form>
            </div>
        </div>
    </div>
</div>
</div>