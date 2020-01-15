<?php

require_once 'window.inc.php';

class Spoilage
{
    private $_user_id;
    private $_clan_id;
    public $_trash_id = 635;
    
    public function __construct($user_id, $clan_id)
    {
        $this->_user_id = $user_id;
        $this->_clan_id = $clan_id;
    }
    
    // Выбор предметов из инвентаря, которые должны на данный момент испортиться
    private function check_inventory()
    {
        $sql = "SELECT 
                    `ue`.`id`, `e`.`name`, `e`.`id` AS `item`
                FROM `uequipment` `ue`
                    JOIN `equipment` `e`
                        ON `ue`.`item` = `e`.`id`
                    JOIN `durability` `d`
                        ON `e`.`durability` = `d`.`durability`
                WHERE 
                    `ue`.`owner` = {$this->_user_id} 
                        AND `e`.`spoilage` = '1'
	                AND TIMESTAMPDIFF(DAY, `ue`.`creationdate`,  NOW()) >= `d`.`max_life_time`";
             
        $res =  _mysql_query($sql) or die(_mysql_error());
                          
        while ($row = _mysql_fetch_assoc($res)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['item']);
          $damaged_items[] = $row;
        }
            
        return $damaged_items;
    }
    
    
    // Порча предметов раз в сутки
    public function damaged_items($elapsed_time_inspection)
    {
        if ($elapsed_time_inspection)
        {
            $items = $this->check_inventory();
            if (is_array($items)) 
            {
                foreach ($items as $value)
                {
                    $count_trash += $this->item_spoilage($value['item'], $this->_user_id);
                    $unspoiled_items_name[] = $value['name'];
                }
                _mysql_query("UPDATE 
                                `players`
                             SET `last_check_spoilage` = NOW()
                                WHERE `id` = {$this->_user_id}") or die(_mysql_error());
                                
                $this->add_log(t::get('Испорченные предметы:').' ' . implode(', ', $unspoiled_items_name)
                                . '. '.t::get('Количество полученного мусора -').' '. $count_trash);
            }
        }
    }
    
    // Получение информации о предметах, которые запрашиваются в кладовой
    public function get_items_data_store($item_ids, $store_type, $owner_id = null)
    {
        if (is_array($item_ids)) {
            $item_ids = implode(', ', $item_ids);
        }
        else {
            $item_ids = $item_ids[0]['id'];
        }
          
        switch ($store_type) 
        {
            case 'bstore':
                $sql = "SELECT 
                            `b`.`id`, `b`.`item`, `b`.`creationdate`, 
                            `d`.`max_life_time` - TIMESTAMPDIFF(DAY, `b`.`creationdate`, NOW()) AS `time_left`,
                            `e`.`name`, `e`.`spoilage`, e.id as eid
                        FROM `bstore` `b`
                            JOIN `equipment` `e`
                                ON `b`.`item` = `e`.`id`
                            JOIN `durability` `d`
                                ON `e`.`durability` = `d`.`durability`
                        WHERE `b`.`player` = {$this->_user_id}
                            AND `b`.`id` IN({$item_ids})";
                break;
                
            case 'cstore':
                $sql = "SELECT 
                            `c`.`id`, `c`.`item`, `c`.`creationdate`, 
                            `d`.`max_life_time` - TIMESTAMPDIFF(DAY, `c`.`creationdate`, NOW()) AS `time_left`,
                            `e`.`name`, `e`.`spoilage`, e.id as eid
                        FROM `cstore` `c`
                            JOIN `equipment` `e`
                                ON `c`.`item` = `e`.`id`
                            JOIN `durability` `d`
                                ON `e`.`durability` = `d`.`durability`
                        WHERE `c`.`clan` = {$this->_clan_id}
                            AND `c`.`id` IN({$item_ids})";
                break;
                
            case 'imarket':
                $sql = "SELECT 
                            `i`.`id`, `i`.`item`, `i`.`creationdate`, 
                            `d`.`max_life_time` - TIMESTAMPDIFF(DAY, `i`.`creationdate`, NOW()) AS `time_left`,
                            `e`.`name`, `e`.`spoilage`, e.id as eid
                        FROM `imarket` `i`
                            JOIN `equipment` `e`
                                ON `i`.`item` = `e`.`id`
                            JOIN `durability` `d`
                                ON `e`.`durability` = `d`.`durability`
                        WHERE `i`.`seller` = {$owner_id}
                            AND `i`.`id` IN({$item_ids})";
                break;
        }
        
        $res =  _mysql_query($sql) or die(_mysql_error());
                         
        while ($row = _mysql_fetch_assoc($res)) {
          $row['name'] = t::getDb('name', 'equipment', 'id', $row['eid']);
          $items[] = $row;
        }
              
        return $items;
    }
    
    // Порча предмета при изъятии из личной кладовки
    //public function spoilage_items($item_ids, $store_type, $store_aux_value = false)
    public function item_spoilage($item, $user_id)
    {
//        $items_data = $this->get_items_store($item_ids, $store_type);
//        foreach ($items_data as $item)
//        {
//            if ($item['spoilage'])
//            {
//                if ($item['time_left'] > 0)
//                {
//                    $filtered_items[] = $item;
//                }
//                else
//                {
//                    $this->make_trash($item['id'], $item['name'], $store_type, $store_aux_value);
//                }
//            }
//            else
//            {
//                return $items_data;
//            }
//        }
//        
//        return $filtered_items;
        
        
        $count_trash = $this->trash_proportion($item);
        
        for ($i = 0; $i < $count_trash; $i++)
        {
            _mysql_query("INSERT INTO
                            `uequipment`(`owner`, `status`, `item`)
                        VALUES({$user_id}, 'U', {$this->_trash_id})") or die(_mysql_error());
        }
        
        return $count_trash;
    }
    
    // Замена предметов на мусор
    //private function make_trash($item_id, $item_name, $store_type, $store_aux_value)
    private function make_trash($item, $user_id, $count_trash)
    {
//        _mysql_query("DELETE FROM `{$store_type}`
//                        WHERE `id` = {$item_id}") or die(_mysql_error());
//        
//        $user_id_for_db = $this->get_user_id_for_db($store_type, $store_aux_value);
//        
//        $this->add_log($item_name, $user_id_for_db);

//        $count_trash = $this->trash_proportion($item);
//        
//        for ($i = 0; $i < $count_trash; $i++)
//        {
//            _mysql_query("INSERT INTO
//                            `uequipment`(`owner`, `status`, `item`)
//                        VALUES({$user_id}, 'U', {$item})") or die(_mysql_error());
//        }
//        
//        return $count_trash;
        
    }
    
    // Соотношения предмета к мусору при замене
    private function trash_proportion($item_id)
    {
        return 1;
    }
    
    // Получение id игрока для записи данных в базу данных
//    private function get_user_id_for_db($store_type, $store_aux_value)
//    {
//        switch ($store_type)
//        {
//            case 'bstore':
//                $user_id_for_db = $this->_user_id;
//                break;
//            case 'cstore':
//                $user_id_for_db = $store_aux_value;
//                break;
//        } 
//        return $user_id_for_db;
//    }
    
    // Добавление записи о порче предметов в лог
    private function add_log($text)
    {
        // По необходимости переделать на класс logdata_* - текст не рулит ввиду мультиязычности
        /*
        $sql = "INSERT INTO 
                    `log`(`owner`, `log`, `CategoryId`)
                VALUES('{$this->_user_id}', '{$text}', '6')";
        $res = _mysql_query($sql) or die(_mysql_error());
        */
    }
}