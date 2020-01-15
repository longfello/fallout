<?php

    $config = array();
    $config_query = _mysql_exec("SELECT config_name, config_value FROM config");
    while ($row = _mysql_fetch_assoc($config_query)) {
        $config[$row['config_name']] = $row['config_value'];
    }

?>