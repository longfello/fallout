<?php
    function getUrlItemsImage($url, $type) {
        $imageDir = 'images/';
        switch($type) {
    		case "D":
    			$img = "food";
    			$small="";
    			break;
    		case "F":
    			$img = "food";
    			$small="";
    			break;
    		case "A":
    			$img = "rmr";
    			$small="small";
    			break;
    		case "W":
    			$img = "wpn";
    			$small="small";
    			break;
    		case "G":
    			$img = "gifts";
    			$small="";
    			break;
            case "M":
    			$img = "misc";
    			$small="";
    			break;
            case "X":
    			$img = "misc";
    			$small="";
    			break;
                
	   }
       return $imageDir . $img  . '/' . $url . $small . '.png';      
    }
    
    function getItemsInventory($usersId) {
        $sql = "SELECT `ue`.`item`, `ue`.`id` AS `uid`, COUNT(`ue`.`id`) AS `count`,
                       `e`.*
	            FROM `uequipment` AS `ue`
                    JOIN `equipment` AS `e` 
                ON `ue`.`item` = `e`.`id`    
                    WHERE `ue`.`owner` = {$usersId}
                        AND `ue`.`status` = 'U'
	            GROUP BY `ue`.`item`
                    ORDER BY CASE `e`.`type` WHEN 'F' THEN 1 WHEN 'D' THEN 0 
                                             WHEN 'W' THEN 2 WHEN 'A' THEN 3  ELSE 9 END, 
                        `e`.`name` ASC";
        $result = _mysql_query($sql) or die(_mysql_error());
        while ($row = _mysql_fetch_object($result)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['id']);
          $data[] = $row;
        }
        
        return $data;
    }
    
    function getPutOnThings($usersId) {
        $sql = "SELECT `ue`.`id` AS `uid`, `e`.*
                    FROM `uequipment` AS `ue`
                        JOIN `equipment` AS `e` 
                            ON `ue`.`item` = `e`.`id`    
                WHERE `ue`.`owner` = {$usersId}
                        AND `ue`.`status` = 'E'
	            GROUP BY `ue`.`item`";
        $result = _mysql_query($sql) or die(_mysql_error());
        while ($row = _mysql_fetch_object($result)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['id']);
          $data[] = $row;
        }
        
        return $data;
    }
    
    function getRecipesInventory($userId) {
        $sql = "SELECT `ur`.`recipe_id`, `rt`.`recipe_image`, COUNT(`ur`.`recipe_id`) AS `count`, 
                       `r`.`recipe_name`, `r`.`recipe_description`, `r`.`using_cnt`
                    FROM `user_recipes` AS `ur`
                        JOIN `recipes` AS `r` ON `ur`.`recipe_id` = `r`.`recipe_id`
                        JOIN `recipe_types` AS `rt` ON `r`.`recipe_type_id` = `rt`.`recipe_type_id`
                WHERE user_id = {$userId} GROUP BY `recipe_id`
                    ORDER BY `rt`.`currency`, `r`.`cost`, `ur`.`using_cnt`";
        $result = _mysql_query($sql) or die(_mysql_error());
        while ($row = _mysql_fetch_assoc($result)) {
          $row['recipe_name']        = t::getDb('recipe_name', 'recipes', 'recipe_id', $row['recipe_id']);
          $row['recipe_description'] = t::getDb('recipe_description', 'recipes', 'recipe_id', $row['recipe_id']);;
          $data[] = $row;
        }
        
        return $data;
    }
    
    function showItemsInfo(&$stat, $eq, $item, $aux_string = '', $key = 0, $compact = true, $str0 = '', $str1 = '') {

	    $equipment = Equipment::model()->findByPk($item->id);
	    if($key==0) $key=mt_rand(1000000,9999999);
    	$type=t::get("Предмет");
    	if($item->weight!=0)
    		$w = t::get('Вес').": " . sprintf("%1.1f",$item->weight/10) . " ".t::get('кг')." &nbsp;&nbsp;&nbsp;&nbsp;";
    	else
    		$w="";
    	
    	// За что продавать будем ;)
    	if($item->mtype=="W")
    		$mtype="tokens";
    	elseif($item->mtype=="P")	
    		$mtype="platinum";
    	else
    		$mtype="gold";//
    	$ava=false;
    	$ava_w =$eq->w_id;
    	$ava_a =$eq->a_id;
    	
    	switch($item->type) {
    		case "D":
    			$type=t::get("Препарат/Напиток");
    			break;
    		case "F":
    			$type=t::get("Продукт");
    			break;
    		case "A":
    			$ava=true;
    			$ava_a =$item->id;
    			$type=t::get("Доспех");
    			break;
    		case "W":
    			$ava=true;
    			$ava_w =$item->id;
    			$type=t::get("Оружие");
    			break;
    		case "G":
    			$type=t::get("Подарок");
    			break;
    	}
    	
    	$ok = true;
    	
    	$stats00 = array("strength","agility","defense", "max_hp" , "max_energy") ; 
    	$stats01 = array(t::get("Aтака"),t::get("Ловкость"),t::get("Защита"), t::get("Макс.&nbsp;здоровье"), t::get("Макс.&nbsp;энергия") ) ;
    	$stats02 = array(t::get("Сила"),t::get("Ловкость"),t::get("Защита"), t::get("Макс.&nbsp;здоровье"), t::get("Макс.&nbsp;энергия") ) ;
    
    	$stats10 = array("hp","energy","pohod") ;
    	$stats11 = array(t::get('Здоровье'),t::get('Энергия'),t::get('Походные очки')) ;
    	
    
    	$val1 = 0;
    	if($compact) {
    		print "<span style=\"display:none;\" id=\"a$key\">";
    	}
    	print "<table style=\"width:300px;margin:0px;padding:0px;\"   cellspacing=0 cellpadding=2 border=0>
    	<tr><td colspan=2 align=center>$type <b>$item->name</b></td></tr>
    	<tr>
    		<td align=\"center\" valign=\"middle\">";
    	if($ava && $compact) {
            gamePrintPlayerCore($stat['id'], $ava_a, $ava_w);
    	} else {
    		print "<img src='".$equipment->getPicture(Equipment::IMAGE_SMALL)."'>";
    	}
    	print	"</td>
    		<td valign=\"top\">
    			<table border=0 width=100% cellspacing=1 cellpadding=1>";
    	$cnt=0;		
    	$goodstyle="style=\"color:lightblue;\"";
    	$badstyle="style=\"color:red;\"";
    
    	for($i=0; $i<count($stats00);$i++)
    	{
    		$value = $item->{"add_".$stats00[$i]};
    		$val1 += $value;
    		if($value>0)
    		{
    			$cnt++;
    			print "<tr><td>{$stats01[$i]}</td><td $goodstyle align=right><b>+$value</b></td></tr>";
    		}
    		elseif($value<0) 
    		{
    			$cnt++;
    			print "<tr><td>{$stats01[$i]}</td><td $badstyle align=right><b>$value</b></td></tr>";
    		}
    	}
            if($item->time_effect>0){
                print "<tr><td>".t::get('Время действия эффекта')."</td><td $goodstyle align=right><b>".$item->time_effect." ".t::get('ч.')."</b></td></tr>";
            }
    	for($i=0; $i<count($stats10);$i++)
    	{
    		$value = $item->{"add_".$stats10[$i]};
    		$val1 += $value;
    		if($value>0)
    		{
    			$cnt++;
    			print "<tr><td>{$stats11[$i]}</td><td $goodstyle align=right><b>+$value</b></td></tr>";
    		}
    		elseif($value<0) 
    		{
    			$cnt++;
    			print "<tr><td>{$stats11[$i]}</td><td $badstyle align=right><b>$value</b></td></tr>";
    		}
    	}	

    	if(intval($item->eprot)!=0)
    	{
    		$cnt++;
    		print "<tr><td>".t::get('Защита от радиации')."</td><td $goodstyle align=right><b>".t::get('Да')."</b></td></tr>";
    	}
    	if($cnt>0) print "<tr><td colspan=2><hr></td></tr>";	
    	$cnt=0;
    	print "<tr><td colspan=2 align=center><b>".t::get('требования')."</b></td></tr>";
    	if($item->minlev > 1)
    	{
    		$cnt=$cnt+1;
    		if($stat[level]<$item->minlev)
    		{
    			$ok = false;			
    			$style=$badstyle;
    		}
    		else
    		{
    			$style=$goodstyle;
    		}		
    		print "<tr><td>".t::get('Уровень')."</td><td $style align=right><b>$item->minlev</b></td></tr>";
    	}
    	
    	for($i=0; $i<count($stats00);$i++)
    	{
    		$value = $item->{"min_".$stats00[$i]};
    		$statvalue = $eq->{"add_".$stats00[$i]} + $stat[$stats00[$i]];
    		if($value>0)
    		{
    			$cnt=$cnt+1;
    			if($statvalue<$value)
    			{
    				$ok = false;			
    				$style=$badstyle;
    			}
    			else
    			{
    				$style=$goodstyle;
    			}
    			print "<tr><td>$stats02[$i]</td><td $style align=right><b>$value</b></td></tr>";
    		}	
    	}
    	
    	if($item->clan_id!=0)
    	{
    		$cnt++;
    		if( $item->clan_id != $stat[clan] )
    		{
    			$ok = false;			
    			$style=$badstyle;
    		}
    		else
    		{
    			$style="";
    		}
    		print "<tr><td  $style  >".t::get('Клан')."</td><td align=right><img alt=\"$item->clan_name\" title=\"$item->clan_name\" src=\"/images/clans/$item->clan_id.gif\"></td></tr>";
    		
    	}
    	
    	if($cnt==0) print "<tr><td colspan=2 align=center>".t::get('нет')."</td></tr>";
    			
    	print "</table>
    		</td>
    	</tr>
    	<tr><td colspan=2><hr></td></tr>
    	<tr><td colspan=2>$item->opis</td></tr>
    	<tr><td colspan=2 align=right>$w ".t::get('Цена за единицу:')." $item->cost <img src=/images/$mtype.png></td></tr>
    	</table>";
    	
    	if($compact)
    	{
    		print "</span>";
    	}
    	
    	return $ok;
    }
    
    
    function parameterForFilter($currentGetParametr) {
        if ($currentGetParametr == 'food') {
            $itype = ' F ';
            $type = 'food';
        } elseif ($currentGetParametr == 'weapon') {
            $itype = ' W ';
            $type = 'weapon';
        } elseif ($currentGetParametr == 'armor') {
            $itype = ' A ';
            $type = 'armor';       
        } elseif ($currentGetParametr == 'other') {
            $itype = ' DMXGB ';                   // сюда дописывать новые типы предметов
            $type = 'other'; 
        } elseif (!isset($currentGetParametr) || $currentGetParametr == 'all') {
            $itype = '1';
            $type = 'all'; 
        } else {
            $itype = '1';
            $type = 'all';
        }
        
        $data = array($itype, $type);
        return $data;  
    }
