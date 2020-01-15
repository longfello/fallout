<?php
  class CleanCommand extends CConsoleCommand {

	public function actionNews(){
		Yii::app()->getModule('material');
		foreach(Language::model()->findAll() as $language){
			t::getInstance()->setLanguage($language->slug);
			foreach(RNews::model()->findAll() as $news) {
				t::getDb('slug', 'news', 'id', $news->id);
				$text = t::getDbSlug('slug', 'news', 'id', $news->id);
				$model = LanguageTranslate::model()->findByAttributes(array('slug' => $text, 'lang_id' => $language->id));

				if (!$model) {
					$model = new LanguageTranslate();
					$model->lang_id = $language->id;
					$model->slug = $text;
				}

				$slug = $news->title?"{$news->title}-{$news->id}":$news->id;
				$model->value = $this->cyrillicToLatin($slug);
				echo("Set slug to #{$news->id} as '{$model->value}'\r\n");
				if (!$model->save()) {
					echo(current($model->getErrors()));
					die();
				}
			}
		}
	}
    public function actionUequipment(){
        $date = time() - 60*60*24*365*1.5; // текущая дата минус 1,5 года
	    $date = date('Y-m-d', $date);

      echo(PHP_EOL);
      $this->log('Proccess old user equipment data (before '.$date.'):');

      $data = $this->queryAll("
SELECT p.id, sum(e.cost) gold
FROM uequipment ue
LEFT JOIN players p ON ue.`owner` = p.id
LEFT JOIN equipment e ON e.id = ue.item
WHERE FROM_UNIXTIME(lpv) < '{$date}' AND ue.`status` = 'U'
GROUP BY p.id", 'Selecting targets...');
	    $cnt = count($data);
	    $this->log($cnt.' users founded. Start processing...');
	    $i = 0; $removed = 0; $gold = 0; $t1 = time(); $q = $tq = 0;
	    foreach($data as $one){
	    	if (round($t1/5) != round(time()/5)) {
	    		$t1 = time();
			    $tq = round($q/5);
			    $q = 0;
		    }
	    	$i++;
		    $p = sprintf("%.1f", round(100*$i/$cnt, 1));
		    $gold += $one['gold'];
		    $this->log($p.'% complete, '.$tq.' queries/sec       ', "\r");
		    $this->exec("UPDATE players SET gold = gold + {$one['gold']} WHERE id = {$one['id']};");
		    $removed += $this->exec("DELETE FROM uequipment WHERE `owner` = {$one['id']} AND `status` = 'U'");
		    $q++;
	    }
	    $this->log('All done!                                           ');
	    $this->log($removed.' items solded for '.$gold.' gold');
    }
    public function actionSocial(){
		echo(PHP_EOL);
		$this->log('Convert social accounts to new structure:');

		$data = $this->queryAll("
SELECT id, provider, provider_uid FROM players WHERE provider > ''", 'Selecting targets...');
	    $cnt = count($data);
	    $this->log($cnt.' users founded. Start processing...');
	    $i = 0; $removed = 0; $gold = 0; $t1 = time(); $q = $tq = 0;
	    foreach($data as $one){
	    	if (round($t1/5) != round(time()/5)) {
	    		$t1 = time();
			    $tq = round($q/5);
			    $q = 0;
		    }
	    	$i++;
		    $p = sprintf("%.1f", round(100*$i/$cnt, 1));
		    $this->log($p.'% complete, '.$tq.' queries/sec       ', "\r");

		    $social = PlayersSocial::model()->findByAttributes(array(
			    'provider' => $one['provider'],
			    'identity' => $one['provider_uid'],
		    ));
		    if (!$social){
		    	$social = new PlayersSocial();
			    $social->player_id = $one['id'];
			    $social->provider  = $one['provider'];
			    $social->identity  = $one['provider_uid'];
			    $social->save();
		    }
		    $q++;
	    }
	    $this->log('All done!                                           ');
    }

    public function exec($query, $msg = false){
	    if ($msg) {
		    $this->log( $msg );
	    }
        return Yii::app()->db->commandBuilder->createSqlCommand($query)->execute();
    }
    public function queryAll($query, $msg = false){
	    if ($msg) {
		    $this->log($msg);
	    }
        return Yii::app()->db->commandBuilder->createSqlCommand($query)->queryAll();
    }
    public function log($msg, $eol = PHP_EOL){
	    echo($msg);
	    echo($eol);
    }
	protected function cyrillicToLatin($text, $toLowCase = TRUE) {
	  $matrix=array(
		  "й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
		  "г"=>"g","ш"=>"sh","щ"=>"shch","з"=>"z","х"=>"h","ъ"=>"",
		  "ф"=>"f","ы"=>"y","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
		  "о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"e","ё"=>"e",
		  "я"=>"ya","ч"=>"ch","с"=>"s","м"=>"m","и"=>"i","т"=>"t",
		  "ь"=>"","б"=>"b","ю"=>"yu",
		  "Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
		  "Г"=>"G","Ш"=>"SH","Щ"=>"SHCH","З"=>"Z","Х"=>"X","Ъ"=>"",
		  "Ф"=>"F","Ы"=>"Y","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
		  "О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"E","Ё"=>"E",
		  "Я"=>"YA","Ч"=>"CH","С"=>"S","М"=>"M","И"=>"I","Т"=>"T",
		  "Ь"=>"","Б"=>"B","Ю"=>"YU",
		  "«"=>"","»"=>""," "=>"-",
		  "\""=>"", "\."=>"", "–"=>"-", "\,"=>"", "\("=>"", "\)"=>"",
		  "\?"=>"", "\!"=>"", "\:"=>"","\r" =>"", "\n" => "",

		  '#' => '', '№' => '',' - '=>'-', '/'=>'-', '  '=>'-',
		  '[' => '-', ']' => '-','+'=>'-', '('=>'-', ')'=>'-', '\''=>'-',
	  );

	  // Enforce the maximum component length
	  $maxlength = 255;
	  $text = implode(array_slice(explode('<br>',wordwrap(trim(strip_tags(html_entity_decode($text))),$maxlength,'<br>',false)),0,1));
	  //$text = substr(, 0, $maxlength);

	  foreach($matrix as $from=>$to)
		  $text=mb_eregi_replace($from,$to,$text);

	// Optionally convert to lower case.
	  if ($toLowCase)
	  {
		  $text = strtolower($text);
	  }

	  return $text;
	}
}

