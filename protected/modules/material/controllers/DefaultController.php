<?php

// class DefaultController extends ModuleController
class DefaultController extends Controller
{
    public $metaDescription = '';
    public $langModel       = t::MODEL_HOME;
    public $layout          = '//layouts/home';
    public $bodyClass       = 'not-home';
    public $active_page     = '';
    public $canonical_url   = '';

    public function init(){
      Yii::app()->theme = 'public';
      $this->pageTitle  = Yii::app()->name;
      Yii::app()->getModule('thumbler');
    }

	/*
public function filters()
{
return array(
	array(
		'COutputCache',
		'duration'=> 30*60, // 30 минут
		'varyByParam'=>array('id', 'section'),
		'varyByExpression' => '(int)Yii::app()->user->isGuest . t::iso()',
	),
	array(
		'CHttpCacheFilter + index + viewn + viewg + viewa + new + article + games + static + faq',
		'lastModified'=>Yii::app()->db->createCommand("SELECT MAX(`date`) FROM news")->queryScalar(),
	),
);
}
	*/

    /**
     * Страница полной новости
     * @param $id
     */
    public function actionViewn($id)
    {
      $this->active_page = '/new';
      $post = RNews::model()->findBySlug($id);

      if ($post) {
        $this->pageTitle = $post->title;
          $this->metaDescription = $post->description;

          $criteria = new CDbCriteria();
        $criteria->order = new CDbExpression('RAND()');
        $criteria->limit = 4;
        $articles = RNews::model()->article()->current()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'NOT id =' . $post->id;
        $criteria->order = 'id DESC';
        $criteria->limit = 4;
        $news = RNews::model()->news()->current()->findAll($criteria);

          $last_criteria = new CDbCriteria();
          $last_criteria->order = 'id DESC';
          $last_news = RNews::model()->news()->current()->find($last_criteria);

          if ($last_news->id==$post->id && isset($_SESSION['userid']))
          {
              Players::model()->updateByPk(Yii::app()->stat->id, array('read_news' => 1));
          }

        $this->render('viewg', array('post' => $post, 'articles' => $articles, 'news' => $news));
      } else throw new CHttpException(404);
    }
    /**
     * Страница полной статьи об игре
     * @param $id
     */
    public function actionViewg($id)
    {
      $post = RNews::model()->findBySlug($id);

      if ($post) {
        $this->pageTitle = $post->title;

        $criteria = new CDbCriteria();
        $criteria->order = new CDbExpression('RAND()');
        $criteria->limit = 4;
        $articles = RNews::model()->article()->current()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $criteria->limit = 4;
        $news = RNews::model()->news()->current()->findAll($criteria);

        $this->render('viewg', array('post' => $post, 'articles' => $articles, 'news' => $news));
      } else throw new CHttpException(404);
    }
    /**
     * Страница полной статьи
     * @param $id
     */
    public function actionViewa($id)
    {
        $this->active_page = '/article';
      $post = RNews::model()->findBySlug($id);
      if ($post) {
        $this->pageTitle = $post->title;
          $this->metaDescription = $post->description;

        $criteria = new CDbCriteria();
        $criteria->order = new CDbExpression('RAND()');
        $criteria->limit = 4;
        $articles = RNews::model()->article()->current()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $criteria->limit = 4;
        $news = RNews::model()->news()->current()->findAll($criteria);

        $this->render('viewa', array('post' => $post, 'articles' => $articles, 'news' => $news));
      } else throw new CHttpException(404);
    }
    /**
     * Статические страницы: about, newuser
     */
    public function actionStatic($section)
    {
        //$this->render('about');

      $post = RNews::model()->find('section = :section', array(':section' => $section));
      if ($post) {
        $this->pageTitle = $post->title;

        if ($section == 'newuser') {
            $this->pageTitle = t::get('metatag_title_'.$section);
            $this->metaDescription = t::get('metatag_desc_'.$section);
        }

        // Данные о рандомных трех статьях
        $criteria = new CDbCriteria();
        $criteria->order = new CDbExpression('RAND()');
        $criteria->limit = 3;
        $articles = RNews::model()->article()->current()->findAll($criteria);

        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $criteria->limit = 4;
        $news = RNews::model()->news()->current()->findAll($criteria);
        if ($section == 'about') {
            $this->active_page = '/page/about';
            $this->bodyClass = 'not-home about-page';
            $this->render('about', array('post' => $post, 'articles' => $articles, 'news' => $news));
        } else {
            $this->active_page = '/newuser';
            $this->bodyClass = 'not-home nowbie-page';
            $this->render('newuser', array('post' => $post, 'articles' => $articles, 'news' => $news));
        }
      } else throw new CHttpException(404);
    }
    /**
     * Статические страницы: faq
     */
    public function actionFaq(){
        $this->active_page = '/faq';
        Yii::app()->getModule('thumbler');
        $this->pageTitle = t::get('metatag_title_faq');
        $this->metaDescription = t::get('metatag_desc_faq');

      $this->bodyClass = 'not-home faq-page';
      $criteria = new CDbCriteria();
      $criteria->order = 'date ASC';
      $criteria->addCondition("section = :section");
      $criteria->addCondition("active = 1");
      $criteria->params[':section'] = 'faq';
      $post = RNews::model()->findAll($criteria);

      $criteria = new CDbCriteria();
      $criteria->order = 'id DESC';
      $criteria->limit = 4;
      $news = RNews::model()->news()->current()->findAll($criteria);

      $this->render('faq', array('faqs' => $post, 'news' => $news));
    }


    /**
     * Страница новостей
     */
    public function actionNew()
	{
        $this->active_page = '/new';
        $this->bodyClass = 'not-home news-page';
        $this->pageTitle = t::get('metatag_title_news');
        $this->metaDescription = t::get('metatag_desc_news');

        $page = Yii::app()->request->getQuery('page');
        if ($page && $page>1) {
            $this->canonical_url = Yii::app()->createAbsoluteUrl('/new');
            $this->pageTitle .= " | ".t::get('страница')." ".$page;
        }

        // Данные о первой записи
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';

        $pagination = new CPagination(RNews::model()->news()->current()->count());
        $pagination->pageSize = 12;
        $pagination->applyLimit($criteria);
        $news =  RNews::model()->news()->current()->findAll($criteria);

		$this->render('new', array('news' => $news, 'pagination' => $pagination));
	}


    /**
     * Страница статей
     */
    public function actionArticle()
    {
        $this->active_page = '/article';
        $this->bodyClass = 'not-home';
        $this->pageTitle = t::get('metatag_title_articles');
        $this->metaDescription = t::get('metatag_desc_articles');

        $page = Yii::app()->request->getQuery('page');
        if ($page && $page>1) {
            $this->canonical_url = Yii::app()->createAbsoluteUrl('/article');
            $this->pageTitle .= " | ".t::get('страница')." ".$page;
        }

        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $pagination = new CPagination(RNews::model()->article()->count());
        $pagination->pageSize = 12;
        $pagination->applyLimit($criteria);
        $news = RNews::model()->article()->current()->findAll($criteria);

        $this->render('article', array('news' => $news, 'pagination' => $pagination));
    }

    /**
     * Страница статей о игре
     */
    public function actionGames()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $pagination = new CPagination(RNews::model()->games()->current()->count());
        $pagination->pageSize = 9;
        $pagination->applyLimit($criteria);
        $news = RNews::model()->games()->current()->findAll($criteria);

        $this->render('games', array('news' => $news, 'pagination' => $pagination));
    }
}