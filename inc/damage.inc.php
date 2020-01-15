<?php

    class Damage
    {
        private $_userId;
        private $_field;
        
        const ONE_DAY = 86400; // 1 сутки в секундах
        
        public function __construct($userId, $store = null)
        {
            $this->_userId = $userId;
            $this->_field = $store;
        }
        
        // Выбор всех предментов инвентаря из бд для поломки вещей
        private function checkInventory()
        {
            $sql = "SELECT `ue`.`id`,
                        FLOOR(((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ue`.`creationdate`))  / " . self::ONE_DAY . ") + 0.5) AS `elapsed_time`,
                        `d`.`max_life_time`, `e`.`name`, e.id as eid
                    FROM `uequipment` `ue`
                        JOIN `equipment` `e`
                    ON `ue`.`item` = `e`.`id`
                        JOIN `durability` `d`
                    ON `e`.`durability` = `d`.`durability`
                        WHERE `ue`.`owner` = '" . $this->_userId . "' 
                    AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`creationdate`)) > '" .  self::ONE_DAY . "'
                        AND `ue`.`damaged` = '0'";
                                 
            $res =  _mysql_query($sql) or die(_mysql_error());
                                 
            while($row = _mysql_fetch_assoc($res)) {
              $row['name'] = t::getDb('name', 'equipment', 'id', $row['eid']);
              $itemsInventory[] = $row;
            }
            
            return $itemsInventory;
        }
        
        // Рассчёт вероятности поломки предметов в инвентаре
        private function probabilityDamageItemsInventory($items)
        {
            if (!empty($items)) {
                foreach ($items as $value) {
                    $randomNumber = mt_rand(0, $value['max_life_time']);
                    if ($randomNumber <= $value['elapsed_time']) {
                        $damageItems[] = $value['id'];
                        $damageNameItems[] = $value['name'];
                    }
                }
                
                // Добавление поломанного предмета в лог
                if (!empty($damageItems)) {
                    $this->addLog($damageItems, $this->_userId);
                }
            }
            return $damageItems;
        }

        // Поломка предметов в инвентаре
        public function damageInventory($lastCheck)
        {
            if ($lastCheck >= self::ONE_DAY) {
                $damageItems = $this->probabilityDamageItemsInventory($this->checkInventory());
                if (!empty($damageItems)) {
                        $itemsID = implode(', ', $damageItems);
                        $sqlUequipment = "UPDATE `uequipment`
                                              SET `damaged` = '1'
                                          WHERE `id` IN({$itemsID})";
                        _mysql_query($sqlUequipment) or die(_mysql_error());
                        
                        $sqlPlayers = "UPDATE `players`
                                           SET `damage_last_check` = NOW()
                                       WHERE `id` = '{$this->_userId}'";
                                       
                        _mysql_query($sqlPlayers) or die(_mysql_error());
                }
            }
        }
        
        
        // Выбор предметов из базы, которые пользователь хочет вынять из кладовки
        private function checkStore($items)
        {
            if (is_array($items)) {
                $itemsID = implode(', ', $items);
            } else {
                $itemsID = $items;
            }
            
            switch ($this->_field) {
                case 'bstore':
                    $sql = "SELECT `b`.`id`, `b`.`item`, `b`.`creationdate`,
                        FLOOR(((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`b`.`creationdate`))  / " . self::ONE_DAY . ") + 0.5) AS `elapsed_time`,
                        `d`.`max_life_time`, `e`.`name`, e.id as eid
                    FROM `bstore` `b`
                        JOIN `equipment` `e`
                    ON `b`.`item` = `e`.`id`
                        JOIN `durability` `d`
                    ON `e`.`durability` = `d`.`durability`
                        WHERE `b`.`player` = '" . $this->_userId . "' 
                            AND `b`.`id` IN({$itemsID})";
                    break;
                case 'cstore':
                    $sql = "SELECT `c`.`id`, `c`.`item`, `c`.`creationdate`,
                        FLOOR(((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`c`.`creationdate`))  / " . self::ONE_DAY . ") + 0.5) AS `elapsed_time`,
                        `d`.`max_life_time`, `e`.`name`, e.id as eid
                    FROM `cstore` `c`
                        JOIN `equipment` `e`
                    ON `c`.`item` = `e`.`id`
                        JOIN `durability` `d`
                    ON `e`.`durability` = `d`.`durability`
                        WHERE `c`.`clan` = '" . $this->_userId . "' 
                            AND `c`.`id` IN({$itemsID})";
                    break;
                case 'imarket':
                    $sql = "SELECT `i`.`id`, `i`.`item`, `i`.`creationdate`,
                        FLOOR(((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`i`.`creationdate`))  / " . self::ONE_DAY . ") + 0.5) AS `elapsed_time`,
                        `d`.`max_life_time`, `e`.`name`, e.id as eid
                    FROM `imarket` `i`
                        JOIN `equipment` `e`
                    ON `i`.`item` = `e`.`id`
                        JOIN `durability` `d`
                    ON `e`.`durability` = `d`.`durability`
                        WHERE `i`.`seller` = '" . $this->_userId . "' 
                            AND `i`.`id` IN({$itemsID})";
                    break;
            }
                    
            $res =  _mysql_query($sql) or die(_mysql_error());
                                 
            while($row = _mysql_fetch_assoc($res)) {
              $row['name'] = t::getDb('name', 'equipment', 'id', $row['eid']);
              $itemsStore[] = $row;
            }
            
            return $itemsStore;
        }
        
        
        // Рассчёт вероятности поломки предметов вынятых из кладовой
        private function probabilityDamageItemsStore($items)
        {
            $transferItems = array(); $damageItems = array();
            $deleteStep = array();
            if (!empty($items)) {
                foreach ($items as $value) {
                    $randomNumber = mt_rand(0, $value['max_life_time']);
                    if ($randomNumber <= $value['elapsed_time']) {
                        $statusDamage = 1;
                        $damageNameItems[] = $value['name'];
                        $damageItems[] = $value['id'];
                    } else {
                        $statusDamage = 0;
                    }
                    
                    $deleteStep[] = $value['id'];
                    
                    $data[] = array('itemsId' => $value['id'],
                                    'damaged' => $statusDamage, 
                                    'creationdate' => $value['creationdate']);
                    
                    //$sqlInseryUequipment = "INSERT INTO `uequipment`
                    //                            (`owner`, `item`, `damaged`, `creationdate`)
                    //                        VALUES ('{$this->_userId}', '{$value['item']}', '{$statusDamage}', '{$value['creationdate']}')";
                    //_mysql_query($sqlInseryUequipment) or die(_mysql_error());
                }
                
                //$deleteItems = implode(', ', $deleteStep);
                //$sqlDeleteBstore = "DELETE FROM `" . $this->_field . "`
                //                        WHERE `id` IN({$deleteItems})";
                //_mysql_query($sqlDeleteBstore) or die(_mysql_error());
                
                // Добавление поломанного предмета в лог
                if (!empty($damageItems)) {
                    $this->addLog($damageItems, $this->_userId);
                }
            }
            return $data;
        }
        
        
        // Поломка предметов, вынятых пользователем из кладовой
        public function damageItems($items)
        {
            return $this->probabilityDamageItemsStore($this->checkStore($items));
        }
        
        // Добавление записи в лог, при поломке предмета
        private function addLog($itemsIds)
        {
            logdata_broken_items::add($this->_userId, $itemsIds);
          /*
            $itemsName = implode(', ', $itemsName);
            $sql = "INSERT INTO `log` (`owner`, `log`, `CategoryId`)
                        VALUES('{$this->_userId}', '".t::encSQL('Сломанные вещи:')." {$itemsName}', '6')";
            $res = _mysql_query($sql) or die(_mysql_error());
          */
        }
        
    }
