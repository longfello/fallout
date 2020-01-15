<div class="referrals-block">
    <p><?= t::get('Вы будете получать 30% крышек, купленных каждым из приглашенных вами игроков.') ?></p>
    <p><?= t::get('Для того, чтобы сделать игрока своим рефералом, просто отправьте ему вот эту ссылку:') ?></p>
    <div class="tab">
        <span id="user_share_link" data-clipboard-target="#user_share_link" class="user-share-link"><?= $share_url ?></span>
        <a id="copy_link" data-clipboard-target="#user_share_link" class="copy-link">
            <span class="copy_finished" style="display: none;"><?= t::get('Скопировано'); ?></span>
            <span class="copy_blank"><?= t::get('Копировать'); ?></span>
        </a>
    </div>
    <p><?= t::get('После регистрации, приведенный вами игрок автоматически станет вашим рефералом.') ?></p>
</div>
<table class="list-referals referrals-table">
    <thead>
    <tr>
        <th width="20%"><?= t::get('Дата регистрации'); ?></th>
        <th width="50%"><?= t::get('Игрок'); ?></th>
        <th width="15%"><?= t::get('Уровень'); ?></th>
        <th width="15%"><?= t::get('Заработано крышек'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($referals as $ref) { ?>
        <tr>
            <td><?= date("Y-m-d H:i:s", $ref->reg_date); ?></td>
            <td><a href="/view.php?view=<?= $ref->id; ?>" target="_blank"><b><?= $ref->user; ?> [<?= $ref->id; ?>]</b></a></td>
            <td><?= $ref->level ?></td>
            <td><?= $ref->getInviterCaps(0.3); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
