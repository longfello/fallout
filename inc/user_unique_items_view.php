<?php

$user_unique_items = array();
$user_unique_items_query = _mysql_exec("SELECT u.item FROM uequipment AS u JOIN equipment AS e ON u.item = e.id WHERE e.status = 'S' AND u.owner = {$unique_items_user_id} AND (e.owner = {$unique_items_user_id} or e.type = 'U') ORDER BY e.owner DESC");
if (_mysql_num_rows($user_unique_items_query))
{
    while ($row = _mysql_fetch_assoc($user_unique_items_query)) {
        $user_unique_items[] = $row['item'];
    }
}
?>


<div class="unique_items_view">
    <p><center><h3><?= t::get('Уникальные предметы и оборудование:') ?></h3></center></p>
    <?php
    if (!empty($user_unique_items))
    {
    ?>
        <ul>
            <?for ($i = 0, $n = count($user_unique_items); $i < $n; $i++):?>
            <li <?=$i > 5 ? ' class="unique_item_hidden"' : ''?>><?=show_unique_item_view(get_data_item($user_unique_items[$i]))?></li>
            <?endfor?>
        </ul>
        <?php if (count($user_unique_items) > 6): ?>
        <a href="#" id="show_all_unique_items"><?= t::get('Показать все предметы') ?></a>
        <?php endif; ?>
    <?php
    }
    elseif (isset($_GET['status'])) {
    ?>
        <?= t::get('<p>У вас еще нет уникальных предметов и оборудования. <br><a href="/unique_items.php">Узнать что это >>></a></p>') ?>
    <?php
    }
    elseif ($view['id'] != $stat['id'])
    {
    ?>
        <?= t::get('<p>У этого персонажа нет уникальных предметов и оборудования. <br><a href="/unique_items.php">Узнать что это >>></a></p>') ?>
    <?php
    }
    else
    {
    ?>
        <?= t::get('<p>У вас еще нет уникальных предметов и оборудования. <a href="/unique_items.php">Узнать что это?</a></p>') ?>
    <?php
    }
    ?>
</div>
