<?php
class BanCommand extends CConsoleCommand {
    public function actionIndex()
    {
        echo(PHP_EOL);
        echo('Convert old bans...');
        echo(PHP_EOL);

        $privacy_ids = array();
        $banned= Yii::app()->db->createCommand('SELECT * FROM banned')->queryAll();
        foreach ($banned as $one_b) {
            $criteria = new CDbCriteria();
            if ($one_b['bandate']==NULL) {
                $criteria->addCondition("`player_id` = '".$one_b['userid']."' AND `until` is NULL");
            } else {
                $criteria->addCondition("`player_id` = '".$one_b['userid']."' AND `until` = TIMESTAMP('".$one_b['bandate']."')");
            }

            $models = BannedPlayers::model()->findAll($criteria);
            if (count($models)==0) {
                $mban = new BannedPlayers();
                $mban->player_id = (int)$one_b['userid'];
                $mban->login = $one_b['user'];
                $mban->chat = ($one_b['chat']=='Yes')?1:0;
                $mban->site = ($one_b['site']=='Yes')?1:0;
                if ($one_b['ip']!='') {
                    $mban->block_ip = 1;
                }
                if ($one_b['bandate']!=NULL) {
                    $mban->until = $one_b['bandate'];
                }
                $mban->admin_id = '4';

                $ban_privacy = Yii::app()->db->createCommand("SELECT * FROM privacy WHERE player_id='".$one_b['userid']."' ORDER BY date DESC")->queryRow();
                if ($ban_privacy) {
                    $mban->reason = $ban_privacy['reason_of_ban'];
                    $mban->comment = $ban_privacy['text'];
                    $mban->created = $ban_privacy['date'];
                    $privacy_ids[] = $ban_privacy['id'];
                }

                $mban->save();

                if ($one_b['ip']!='') {
                    $ipnum = ip2long($one_b['ip']);
                    if ($ipnum != -1 && $ipnum !== FALSE) {
                        $mbanip = new BannedIp();
                        $mbanip->ban_id = $mban->id;
                        $mbanip->ip = sprintf("%u", $ipnum);
                        $mbanip->save();
                    }
                }
            }
        }

        $privacy = Yii::app()->db->createCommand("SELECT * FROM privacy WHERE id NOT IN (".implode(',',$privacy_ids).")")->queryAll();
        foreach ($privacy as $one_p) {
            $criteria = new CDbCriteria();
            $criteria->addCondition("`player_id` = '".$one_p['player_id']."' AND `until` = '".$one_p['date']."'");

            $models = BannedPlayers::model()->findAll($criteria);
            if (count($models)==0) {
                $mban = new BannedPlayers();
                $mban->player_id = (int)$one_p['player_id'];
                $mban->login = $one_p['player_nickname'];
                $mban->chat = 1;
                $mban->site = 1;
                $mban->advert = 1;
                $mban->until = $one_p['date'];
                $mban->comment = $one_p['text'];
                $mban->reason = $one_p['reason_of_ban'];
                $mban->admin_id = '4';
                $mban->created = $one_p['date'];
                $mban->save();
            }
        }
    }
}