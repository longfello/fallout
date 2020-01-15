<?php

//$days = $db->get_var("SELECT TIMESTAMPDIFF(DAY, DATE, NOW()) FROM rev_respawn WHERE player_id = {$_SESSION["userid"]}");
$days = $db->get_var("SELECT UNIX_TIMESTAMP() - lpv FROM players WHERE id = {$userid}");

/*if ($days || $days === null)
{
    $db->query("START TRANSACTION");
    _mysql_query("update players set ewins = CASE WHEN wins>399 THEN 5 WHEN wins>239 THEN 4 WHEN wins>179 THEN 3 WHEN wins>109 THEN 2 WHEN wins>49 THEN 1 ELSE 0 END WHERE id = {$_SESSION["userid"]}");
    _mysql_query("update players set
            lstate = 0 ,
            ops=ops + CASE WHEN  mines > 0 and  ops < 35 THEN 10 ELSE 0 END,
            age=age+1,
            energy=GREATEST(max_energy,energy),
            pohod=CASE WHEN pleft<=1 THEN 320 ELSE 420 END,
            napad=ewins + CASE WHEN pleft<=1 THEN 6 ELSE 11 END,
            pleft=CASE WHEN pleft>0 THEN pleft-1 ELSE 0 END
            WHERE id = {$_SESSION["userid"]}
    ");
    $db->query("INSERT INTO rev_respawn (player_id, date) VALUES ({$_SESSION["userid"]}, NOW()) ON DUPLICATE KEY UPDATE date = NOW()");
    $db->query("COMMIT");
}*/

if ($days > 108000)
{
    _mysql_query("update players set
		lstate = 0 ,
		ops=ops + CASE WHEN  mines > 0 and  ops < 35 THEN 10 ELSE 0 END,
		age=age+1,
		energy=GREATEST(max_energy,energy),
		hp=GREATEST(max_hp,hp),
		pohod=CASE WHEN pleft<=1 THEN 320 ELSE 420 END,
		napad=ewins + CASE WHEN pleft<=1 THEN 6 ELSE 11 END,
		pleft=CASE WHEN pleft>0 THEN pleft-1 ELSE 0 END
		WHERE id = {$_SESSION["userid"]}
		");
}

?>