<?php

// Количество записей выводимых на страницу
$num = 50;
// Общее число страниц
$total = ceil($player_count_data['cnt'] / $num);
$page = intval($page);
// Если значение $page меньше единицы или отрицательно переходим на первую страницу
// А если слишком большое, то переходим на последнюю
if (empty($page) or $page < 0) $page = 1;
if ($page > $total) $page = $total;
// Вычисляем начиная к какого номера следует выводить сообщения
$start = $page * $num - $num;

$pagination_line = '';
$pagination_line .= '<ul class="page_line">';
for ($i = 0; $i < $total; $i++)
{
    $cur_page_class = ' class="current_page"';
    $cur_page_active = ($page == ($i + 1)) ? $cur_page_class : '';

    $pagination_line .= '<li><a ' . $cur_page_active . 'href="battle.php?' . $page_url . '&page=' . ($i + 1) .  '">' . ($i + 1) . '</li>';
}
$pagination_line .= '</ul>';

?>