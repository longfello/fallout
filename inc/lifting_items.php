<?php
//========================================================================================
//  Получение подъемных при достижении определённого уровня
//========================================================================================
require_once 'functions/general.php';

    $given_items_log = array(
        'item_no_bank' => array(),
        'gold_no_bank' => 0,
        'item_bank' => array(),
        'gold_bank' => 0,
        'platinum' => 0,
        'stim' => 0
    );

    foreach ($new_user_help_all_data as $new_user_help_data)
    {
        // Наименование предмета, который выдаётся
        if ($new_user_help_data['item']) {
//          $item_name = _mysql_result(_mysql_exec("SELECT `name` FROM `equipment` WHERE `id` = {$new_user_help_data['item']}"), 0, 0);
          $item_name = t::getDb('name', 'equipment', 'id', $new_user_help_data['item']);
        }

        // Выдача на руки: предметы в инвентарь, а золото добавить к текущему
        if ($new_user_help_data['bank'] == 'N')
        {
            if ($new_user_help_data['item'])
            {
                for ($i = 0; $i < $new_user_help_data['item_count']; $i++)
                {
                    if (($new_user_help_data['item'] == 439 || $new_user_help_data['item'] == 498) && !$i)
                    {
                        _mysql_exec("INSERT INTO `uequipment` (`owner`, `status`, `item`)
                                    VALUES ({$stat['id']}, 'E', {$new_user_help_data['item']})");
                    }
                    else
                    {
                        _mysql_exec("INSERT INTO `uequipment` (`owner`, `status`, `item`)
                                    VALUES ({$stat['id']}, 'U', {$new_user_help_data['item']})");
                    }
                }

                array_push($given_items_log['item_no_bank'], "{$item_name} ({$new_user_help_data['item_count']} шт.)");
            }
            if ($new_user_help_data['gold'])
            {
                _mysql_exec("UPDATE `players` SET `gold` = `gold` + {$new_user_help_data['gold']}
                                WHERE `id` = {$stat['id']} LIMIT 1");

                $given_items_log['gold_no_bank'] += $new_user_help_data['gold'];
            }
        }

        // Выдача в банк: предметы в личную кладовую, золото в банк
        elseif ($new_user_help_data['bank'] == 'Y')
        {
            if ($new_user_help_data['item'])
            {
                if (($new_user_help_data['item'] == 439 || $new_user_help_data['item'] == 498) && !$i)
                {
                    for ($i = 0; $i < $new_user_help_data['item_count']; $i++)
                    {
                        _mysql_exec("INSERT INTO `uequipment` (`owner`, `status`, `item`)
                                    VALUES ({$stat['id']}, 'E', {$new_user_help_data['item']})");
                    }
                }
                else
                {
                    _mysql_exec("INSERT INTO `bstore` (`item`, `player`) VALUES ({$new_user_help_data['item']}, {$stat['id']})");
                }

                array_push($given_items_log['item_bank'], "{$item_name} ({$new_user_help_data['item_count']} шт.)");
            }
            if ($new_user_help_data['gold'])
            {
                _mysql_exec("UPDATE `players` SET `bank` = `bank` + {$new_user_help_data['gold']}
                                WHERE `id` = {$stat['id']} LIMIT 1");

                $given_items_log['gold_bank'] += $new_user_help_data['gold'];
            }
        }

        // Выдача крышек
        if ($new_user_help_data['plat']) {
            _mysql_exec("UPDATE `players` SET `platinum` = `platinum` + {$new_user_help_data['plat']}
                                WHERE `id` = {$stat['id']} LIMIT 1");

            $given_items_log['platinum'] += $new_user_help_data['plat'];
        }

        // Добавление дней использования стимулятора
        if ($new_user_help_data['stim']) {
            _mysql_exec("UPDATE `players` SET `pleft` = `pleft` + {$new_user_help_data['stim']}
                            WHERE `id` = {$stat['id']} LIMIT 1");

            $given_items_log['stim'] += $new_user_help_data['stim'];
        }
    }


    // Добавить информацию в базу, что игрок уже получал подъёмные на текущий уровень
    _mysql_exec("INSERT INTO `newuser_completed` SET `user_id` = {$stat['id']}, `level` = {$new_user_help_data['level']}");


    // Генерация сообщения для лога
    $help_data_log_message = array();
    if ($given_items_log['item_no_bank']) {
        $help_data_log_message[] = implode(', ', $given_items_log['item_no_bank']);
    }
    if ($given_items_log['item_bank']) {
        $help_data_log_message[] = implode(', ', $given_items_log['item_bank']) . ' в банк';
    }
    if ($given_items_log['gold_no_bank']) {
        $help_data_log_message[] = $given_items_log['gold_no_bank'] . '<img width="15" height="15" src="/images/gold.png">';
    }
    if ($given_items_log['gold_bank']) {
        $help_data_log_message[] = $given_items_log['gold_bank'] . '<img width="15" height="15" src="/images/gold.png"> в банк';
    }
    if ($given_items_log['platinum']) {
        $help_data_log_message[] = $given_items_log['platinum'] . '<img width="15" height="15" src="/images/platinum.png">';
    }
    if ($given_items_log['stim']) {
        $help_data_log_message[] =  'Стимулятор (+' . $given_items_log ['stim'] . ' дней)' ;
    }


    // Выдаваемые предметы в виде иконок
    $lifting_items_res = _mysql_exec("SELECT `item`, `item_count`, `plat`, `gold`, `bank`, `stim` FROM `newuser` WHERE `level` = {$stat['level']}");
    $climb_items = array();
    while ($row = _mysql_fetch_assoc($lifting_items_res)) {
        $climb_items[] = $row;
    }
    ob_start();
    ?>
    <div class="itemp_content">
        <ul>
            <?foreach ($climb_items as $value):?>
                <li>
                    <?=get_view_items($value['item'], $value['plat'], $value['gold'], $value['stim'])?>
                    <div class="item_info">
                        <?=($value['stim'] ? t::get('на %s день', $value['stim']) : $value['item_count']) .
                        ($value['item'] ? ' '.t::get('шт.') : '')?> <?=($value['stim'] ? '' : get_storage_text($value['bank']))?>
                    </div>
                </li>
            <?endforeach?>
        </ul>
    </div>


    <?php
    $item_icons_content = ob_get_clean();

    if ($stat['level'] >= $config['player_bonus']) {
        $_SESSION['bonus_window'] = true;
    }
