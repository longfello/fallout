<?php
  class TranslateCommand extends CConsoleCommand {

    var $literals = array(
      't::get',
      't::encHtml',
      't::encSQL',
      't::encJs',
      't::plural',
    );

    var $table_processed = array();

    var $functions = array(
        'get',
        'encHtml',
        'encSQL',
        'encJs',
        'plural',
    );
    var $file = '';
    var $cnt = 0;

    public function actionIndex()
    {
      // renders the view file 'protected/views/site/index.php'
      // using the default layout 'protected/views/layouts/main.php'
      // $this->render('index');

//      $query = "DELETE FROM rev_language_translate WHERE slug LIKE '@@@%'";
//      Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->execute();

      $Directory = new RecursiveDirectoryIterator('./..');
      $Iterator = new RecursiveIteratorIterator($Directory);
      $files = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

      foreach($files as $filename => $current){
        $this->process_file($filename);
      }

      echo("\n\n\ndone!\n\n");
    }

    private function process_file($filename){
      if (strpos($filename, 'MLActiveForm.php')) return;
//      echo(str_pad($filename, 120));
      $this->file = $filename;
      $this->cnt = 0;
      $content = file_get_contents($filename);
      $tokens = token_get_all($content);
      foreach ($tokens as $key => $token) {
        if (is_array($token)) {
          if ((token_name($token[0]) == 'T_STRING') && ($token[1] == 't')) {
            $this->process_line($tokens, $key);
          }

          if ($token[1] == '$title' && $token[2] < 10) {
            $this->process_line2($tokens, $key);
          }

        }
      }
//      if ($this->cnt) echo( "\r\n" );
//                 else echo( "\r" );
    }

    private function process_line2($tokens, $index){
      $notfounded = true; $i=1; $literal = false;
      do {
        $ii = $index + $i;
        if (is_array($tokens[$ii]) && token_name($tokens[$ii][0]) == 'T_CONSTANT_ENCAPSED_STRING') {
          $notfounded = false;
          $literal = $tokens[$ii][1];
        }

        $i++;
        $notfounded = $notfounded && ($i < 7);
      } while ($notfounded);

      if ($literal) {
        $delimiter = substr($literal, 0, 1);
        $literal = trim($literal, $delimiter);

        $unescape_literal = ($delimiter == '"')?'\"':"\'";
        $literal = str_replace($unescape_literal, $delimiter, $literal);

        if ($literal) {
          foreach(t::getInstance()->languages as $language){
            t::getInstance()->setLanguage($language->slug);
            t::get($literal);
          }
          $this->reportFind();
        }
      }
    }
    private function process_line($tokens, $index){
      if (strpos($this->file, 'TranslateCommand.php')) return;
      $isColon = isset($tokens[$index+1])?$tokens[$index+1]:false;
      if ($isColon && is_numeric($isColon[0]) && token_name($isColon[0]) == 'T_DOUBLE_COLON'){
        $function = isset($tokens[$index+2])?$tokens[$index+2]:false;
        if ($function && is_numeric($function[0]) && in_array($function[1], $this->functions)) {

          $ii = ($tokens[$index+3] == '(')?4:3;
          $string = isset($tokens[$index+$ii])?$tokens[$index+$ii]:false;
          if ($string  && is_numeric($string[0]) && token_name($string[0]) == 'T_CONSTANT_ENCAPSED_STRING'){
            $literal = $string[1];
            $delimiter = substr($literal, 0, 1);
            $literal = trim($literal, $delimiter);

            $unescape_literal = ($delimiter == '"')?'\"':"\'";
            $literal = str_replace($unescape_literal, $delimiter, $literal);

            foreach(t::getInstance()->languages as $language){
              t::getInstance()->setLanguage($language->slug);
              t::get($literal);
            }
            $this->reportFind();
          }
        } elseif ($function && is_numeric($function[0]) && ($function[1] == 'getDb')) {
          $args = array();
          $i = $index + 4;
          do {
            if (is_array($tokens[$i]) && ($tokens[$i][0] == T_CONSTANT_ENCAPSED_STRING)) {
              $args[] = trim($tokens[$i][1], '"\'');
            }
            $i++;
          } while ((count($args) < 3) && (isset($tokens[$i])));

          if (count($args) == 3) {
            try {
              list($field, $table, $pk) = $args;
              if (!in_array($table.'_'.$pk, $this->table_processed)) {
                $this->table_processed[] = $table.'_'.$pk;
                $query = "select $pk as id FROM $table";
                $res = Yii::app()->getDb()->commandBuilder->createSqlCommand($query)->queryAll();
                $i = 1; $count = count($res);
                foreach($res as $one) {
  //                echo( "\r" );
  //                echo( str_pad("Process table `$table`", 120)." $i/$count\r");
                  foreach(t::getInstance()->languages as $language){
                    $id = $one['id'];
                    t::getInstance()->setLanguage($language->slug);
                    t::getDb($field, $table, $pk, $id);
                  }
                  $i++;
                  $this->cnt++;
                }
              }
            } catch(Exception $error){
              var_dump($args);
              var_dump($index);
              die();
            };
            $this->reportFind();
          }
        }
      }
    }

    protected function reportFind(){
      $this->cnt++;
//      echo( "\r" );
//      echo(str_pad($this->file, 120).' - '.str_pad($this->cnt, 10));
    }
  }