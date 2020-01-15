<?php

    function getItemsBStore($usersId) {
        $sql = "SELECT `b`.`item`, `b`.`id` AS `uid`, COUNT(`b`.`id`) AS `count`,
                       `e`.*
	            FROM `bstore` AS `b`
                    JOIN `equipment` AS `e` 
                ON `b`.`item` = `e`.`id`    
                    WHERE `b`.`player` = {$usersId}
	            GROUP BY `b`.`item`
                    ORDER BY CASE `e`.`type` WHEN 'A' THEN 1 WHEN 'W' THEN 0 
                                             WHEN 'F' THEN 2 WHEN 'D' THEN 3  ELSE 9 END, 
                        `e`.`type`, `e`.`cost`, `e`.`name` ASC";
        $result = _mysql_query($sql) or die(_mysql_error());
        
        while ($row = _mysql_fetch_object($result)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['id']);
          $data[] = $row;
        }
        
        return $data;
    }
    
    function getItemsCStore($clanId) {
        $sql = "SELECT `c`.`item`, `c`.`id` AS `uid`, COUNT(`c`.`id`) AS `count`,
                       `e`.*
	            FROM `cstore` AS `c`
                    JOIN `equipment` AS `e` 
                ON `c`.`item` = `e`.`id`    
                    WHERE `c`.`clan` = {$clanId}
	            GROUP BY `c`.`item`
                    ORDER BY CASE `e`.`type` WHEN 'A' THEN 1 WHEN 'W' THEN 0 
                                             WHEN 'F' THEN 2 WHEN 'D' THEN 3  ELSE 9 END, 
                        `e`.`type`, `e`.`cost`, `e`.`name` ASC";
        $result = _mysql_query($sql) or die(_mysql_error());
        
        while ($row = _mysql_fetch_object($result)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['id']);
          $data[] = $row;
        }
        
        return $data;
    }
    