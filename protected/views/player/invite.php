<?php if (count($user_contacts)==0) {?>
    <div class="import-contact">
        <?= t::get('Импортируйте свои контакты из:'); ?>
        <a href="/player/invite/google" class="gmail-contacts">Gmail</a>
        <!--a href="/player/invite/yahoo" class="yahoo-contacts"><?= t::get('Почты Yahoo!'); ?></a-->
        <!--a href="/player/invite/live" class="outlook-contacts"><?= t::get('Почты Outlook'); ?></a-->
    </div>
    <div class="else-invite-wrap"><span class="else-invite"><?= t::get('или'); ?></span></div>
    <div class="share-wrap">
        <p><?= t::get('Поделитесь своей ссылкой:'); ?></p>
        <input type="text" id="user_share_link" data-clipboard-target="#user_share_link" class="user-share-link" value="<?= $share_url ?>" readonly="">
        <a id="copy_link" data-clipboard-target="#user_share_link" class="copy-link">
            <span class="copy_finished" style="display: none;"><?= t::get('Скопировано'); ?></span>
            <span class="copy_blank"><?= t::get('Копировать'); ?></span>
        </a>
        <a href="http://www.facebook.com/sharer.php?u=<?= urlencode($share_url."&social=fb"); ?>" target="_blank" class="facebook-link">Facebook</a>
        <?php if (t::iso()=='ru'){?>
            <a href="https://vk.com/share.php?url=<?=  urlencode($share_url."&social=vk"); ?>" target="_blank" class="vk-link">Вконтакте</a>
        <?php } ?>
    </div>
<?php } else { ?>
    <a href="/player/invite" class="change-invite"><?= t::get('Выбрать другой способ приглашения'); ?></a>
    <div>
        <form action="/player/invitegroup" method="post" id="invite_email">
            <div class="wrapper-invite_email">
                <table cellspacing="0" cellpadding="0" class="list-invited-other">
                    <thead>
                    <tr>
                        <th width="3%"></th>
                        <th width="62%">Email</th>
                        <th width="35%"><?= t::get('Имя'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $cur=0;
                    foreach ($user_contacts as $contact) {
                        if ($contact->email) {
                            $player = Players::model()->findByAttributes(['email'=>$contact->email]);
                            if (!$player) {
                                $cur++; ?>
                                <tr>
                                    <td><input type="checkbox" value="<?= $contact->email ?>" name="emails[]" id="contact_<?= $cur; ?>"></td>
                                    <td><label title="<?= $contact->email ?>" for="contact_<?= $cur; ?>"><?= $contact->email ?></label></td>
                                    <td><label for="contact_<?= $cur; ?>"><?= $contact->displayName; ?></label></td>
                                </tr>
                        <?php
                            }
                        }
                    } ?>
                    </tbody>
                </table>
                <div class="errors"></div>
                <a href="#" id="check_all_invites"><?= t::get('Выбрать всех'); ?></a>
                <a href="#" id="uncheck_all_invites"><?= t::get('Отменить выбор'); ?></a>
            </div>
            <input type="submit" value="<?= t::get('Пригласить'); ?>" class="invite-submit list-invite"/>
        </form>
    </div>
<?php } ?>
<?php

foreach (Yii::app()->user->getFlashes() as $key => $one) {
    if ($key == 'error') {
        echo Window::error($one);
    } else {
        echo Window::highlight($one);
    }
}


?>
<div>
 <span class="sended-invite"><?= t::get('Отправленные приглашения:'); ?></span>
 <table class="list-invited">
     <thead>
        <tr>
            <th width="15%"><?= t::get('Дата'); ?></th>
            <th width="40%">Email</th>
            <th width="15%"><?= t::get('Можно получить крышек'); ?></th>
            <th width="15%"><?= t::get('Получено крышек'); ?></th>
            <th width="15%"><?= t::get('Зарегистрировался'); ?></th>
        </tr>
     </thead>
     <tbody>
        <?php foreach ($invites as $inv) { ?>
            <tr>
                <td><?= $inv->created_at; ?></td>
                <td title="<?= $inv->email ?>"><?= $inv->email ?></td>
                <td><?= ($caps_reg->config_value*$invite_bonus_levels_count)-$inv->caps_reg; ?></td>
                <td><?= $inv->caps_send+$inv->caps_reg ?></td>
                <td><?= ($player && $player->checkRefExist($inv->email))?t::get("Да"):t::get("Нет"); ?></td>
            </tr>
        <?php } ?>
     </tbody>
 </table>
    <div id="pagination-invite" class="pagination">
        <?php $this->widget('application.components.widgets.HomePager', array(
            'pages'=>$pages,
        ));
        ?>
    </div>
</div>
