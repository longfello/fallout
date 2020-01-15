<?php

/* Аватар для чата */
$chat_avatar_id = $db->get_var("SELECT avatar_id FROM rev_chat_avatar WHERE player_id = {$stat['id']}");
if (!$chat_avatar_id)
    $db->query("INSERT INTO rev_chat_avatar (player_id) VALUES ({$stat['id']})");


/* Установка ачивок */
// Ачивка: "Headhunter"
if ($achieve_id = RAchieveRules::headhunter())
    RAchieve::set($achieve_id);
// Ачивка: "Headhunter"
if ($achieve_id = RAchieveRules::murderer())
    RAchieve::set($achieve_id);

?>