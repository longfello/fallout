<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $title_news
 * @property string $news
 * @property string $date
 * @property string $img
 * @property string $section
 * @property integer $active
 * @property integer $slug
 *
 * @method RNews current()
 * @method RNews news()
 * @method RNews games()
 * @method RNews article()
 */

class RNews extends MLModel
{
    public $image;
    public $MLFields = array('news', 'title', 'title_news', 'description', 'slug');

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'news';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title_news, section', 'required'),
            array('title, description, section, slug', 'length', 'max'=>255),
            array('img', 'length', 'max'=>150),
            array('date, active', 'safe'),
            array('image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=> true, 'safe' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, active, description, image, title_news, news, date, img, section, slug', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
          'id' => t::get('ID'),
          'title' => t::get('Заголовок'),
          'description' => 'meta description',
          'title_news' => t::get('Заголовок новости'),
          'news' => t::get('Текст новости'),
          'date' => t::get('Дата публикации'),
          'img' => t::get('Картинка новости'),
          'section' => t::get('Раздел'),
          'active' => t::get('Статус'),
          'slug' => t::get('ЧПУ URL'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->order = "date DESC";
        $criteria->compare('id',$this->id);
//        $criteria->compare('title',$this->title,true);
//        $criteria->compare('description',$this->description,true);
        $criteria->compare('title_news',$this->title_news,true);
//        $criteria->compare('news',$this->news,true);
        $criteria->compare('img',$this->img,true);
        $criteria->compare('section', $this->section,false);
        $criteria->compare('active',$this->active,false);

        if ($this->title) {
        	$query = "
	        SELECT foo.id, tr.value 
	        FROM (SELECT t.id, CONCAT('@@@news@@title@@id@@', t.id) slug FROM `news` `t`) as foo
			LEFT JOIN (SELECT * FROM rev_language_translate WHERE slug LIKE '@@@news@@title@@id@@%') tr ON tr.slug = foo.slug
			WHERE (tr.value LIKE '%{$this->title}%') 
			ORDER BY tr.value
			LIMIT 100";
        	$criteria->join .= " INNER JOIN ($query) as ml_title ON ml_title.id = t.id ";
        }
        if ($this->description) {
        	$query = "
	        SELECT foo.id, tr.value 
	        FROM (SELECT t.id, CONCAT('@@@news@@description@@id@@', t.id) slug FROM `news` `t`) as foo
			LEFT JOIN (SELECT * FROM rev_language_translate WHERE slug LIKE '@@@news@@description@@id@@%') tr ON tr.slug = foo.slug
			WHERE (tr.value LIKE '%{$this->description}%') 
			ORDER BY tr.value
			LIMIT 100";
        	$criteria->join .= " INNER JOIN ($query) as ml_description ON ml_description.id = t.id ";
        }
        if ($this->news) {
        	$query = "
	        SELECT foo.id, tr.value 
	        FROM (SELECT t.id, CONCAT('@@@news@@news@@id@@', t.id) slug FROM `news` `t`) as foo
			LEFT JOIN (SELECT * FROM rev_language_translate WHERE slug LIKE '@@@news@@news@@id@@%') tr ON tr.slug = foo.slug
			WHERE (tr.value LIKE '%{$this->news}%') 
			ORDER BY tr.value
			LIMIT 100";
        	$criteria->join .= " INNER JOIN ($query) as ml_news ON ml_news.id = t.id ";
        }

        if ($this->date){
	        $date = date('Y-m-d 00:00:00', strtotime($this->date));
	        $criteria->compare('date',$date);
//	        var_dump($this->date); die();
        }

//	    $criteria->compare('title',$this->title,true);
//	    $criteria->compare('description',$this->description,true);
//	    $criteria->compare('news',$this->news,true);
//	    $criteria->join .= ' 1 ';
	    $criteria->distinct = true;

      return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
          'Pagination' => array (
              'PageSize' => 50 //edit your number items per page here
          ),
      ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RNews the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function afterFind() {
	    $result = parent::afterFind(); // TODO: Change the autogenerated stub
	    $doc = new DOMDocument();
	    @$doc -> loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $this->news); // url of the html file
	    $links = $doc->getElementsByTagName('a');

	    foreach($links as $link) {
		    $href = $link->getAttribute('href');
		    if (substr(trim($href), 0, 4) == 'http') {
			    $link->setAttribute('rel', 'nofollow');
		    }
	    }

	    $this->news = $doc->saveHTML();
	    return $result;
    }

	public function scopes()
    {
      if (Yii::app() instanceof CConsoleApplication){
	      $current = 'date<NOW() and active=1';
	  } else {
	      $current = Yii::app()->user->checkAccess('admin')?"":'date<NOW() and active=1';
      }
      return array(
          'current' => array(
              'condition' => $current,
          ),
          'news' => array(
              'condition' => 'section="news"',
          ),
          'games' => array(
              'condition' => 'section="games"',
          ),
          'article' => array(
              'condition' => 'section="article"'
          )
      );
    }

  public function getImg(){
    if($this->img) {
      return '/images/news/'.$this->img;
    } else {
      return '/images/slide_01.jpg';
    }
  }

    protected function afterSave() {
	    parent::afterSave();
	    foreach(Language::model()->findAll() as $language){
	    	$slug =$this->getML('slug', $language->slug);
		    if (!trim($slug)) {
			    $title = $this->getML('title_news', $language->slug);
			    if (!trim($title)){
				    $title = $this->getML('title', $language->slug);
			    }
			    $title = $title?$title.'-'.$this->id:$this->id;
			    $this->setML('slug', $this->transliterate($title), $language->slug);
		    }

            $desc = $this->getML('description', $language->slug);
            if (!trim($desc)) {
                $content = $this->getML('news', $language->slug);
                $new_desc = mb_substr(trim(str_replace(array('"',"'"),'',strip_tags(html_entity_decode($content)))),0,160);
                $this->setML('description', $new_desc, $language->slug);
            }
	    }
    }

    protected function beforeSave()
    {
        if ($this->section=='news') {
	        if($this->isNewRecord)
            {
                if ($this->active) {
                    Yii::app()->db->createCommand("UPDATE `players` SET `read_news` = 0")->execute();
                }
            } else {
                $oldnews = self::findByPk($this->id);
                if (!$oldnews->active && $this->active) {
                    Yii::app()->db->createCommand("UPDATE `players` SET `read_news` = 0")->execute();
                }
            }
        }
       return parent::beforeSave();
    }

	public function findBySlug($slug){
		$prefix = "@@@news@@slug@@id@@";
		$query = "
SELECT * 
FROM rev_language_translate
WHERE `value` = '{$slug}' AND slug LIKE '{$prefix}%'
ORDER BY lang_id = ".t::getInstance()->language->id." DESC
";
		$data = Yii::app()->db->createCommand($query)->queryAll();
		foreach ($data as $one) {
			$id = (int)str_replace($prefix, '', $one['slug']);

			if ($one['lang_id'] == t::getInstance()->language->id) {
				return RNews::model()->current()->findByPk($id);
			}

			$model = RNews::model()->findByPk($id);
			/** @var $model RNews */
			if ($model) {
				$url = $model->getURL();
				if ($url) {
					Yii::app()->request->redirect($url);
				}
			}
		}

		if (strpos($slug, '-')) {
			$chains = explode('-', $slug);
			$slug = array_pop($chains);
		}

		if (is_numeric($slug)){
			$model = RNews::model()->findByPk($slug);
			/** @var $model RNews */
			if ($model) {
				$url = $model->getURL();

				if ($url) {
					Yii::app()->request->redirect($url);
				}
			}
		}

		return false;
	}

	public function getURL(){
		$type = false;
		switch ($this->section){
			case 'news': $type = 'new'; break;
			case 'newuser': $type = 'new'; break;
			case 'article': $type = 'article'; break;
			case 'games': $type = 'games'; break;
			default: $type = 'new';
		}
		if ($type) {
			return '/'.$type.'/'.$this->t('slug');
		}
		return false;
	}
}