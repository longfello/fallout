<?php

    function getItemsImarket($userId) {
        $sql = "SELECT `i`.`item`, COUNT(`i`.`id`) AS `count`, MIN(`i`.`mcost`) AS `mincost`, 
                           MAX(`i`.`mcost`) AS `maxcost`,  CEIL(AVG(`i`.`mcost`)) AS `avgcost` , 
                       `e`.*, IFNULL(`c`.`id`, 0) AS `clan_id`, `c`.`name` AS `clan_name`
                FROM `imarket` AS `i`
                    JOIN `equipment` AS `e` 
		                ON `e`.`id` = `i`.`item`
	                LEFT JOIN `clans` AS `c` 
                        ON `c`.`id` = `e`.`clan`
                GROUP BY `i`.`item`
                    ORDER BY CASE `e`.`type` WHEN 'A' THEN 1 WHEN 'W' THEN 0 WHEN 'F' THEN 2 
                                             WHEN 'D' THEN 3  ELSE 9 END, 
                                  `e`.`type`, `e`.`cost`, `e`.`name` ASC";
        $result = _mysql_query($sql) or die(_mysql_error());
        
        while ($row = _mysql_fetch_object($result)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['id']);
            $data[] = $row;
        }
        
        return $data;
    }




