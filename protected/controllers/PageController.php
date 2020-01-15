<?php

class PageController extends Controller
{
	public $metaDescription = '';
	public $langModel       = t::MODEL_HOME;
	public $layout          = '//layouts/home';
	public $bodyClass       = 'not-home about-page';
	public $loginForm       = false;
	public $active_page     = '';

	public function init(){
		Yii::app()->theme = 'public';
		Yii::app()->getModule('material');
		Yii::app()->getModule('thumbler');
	}

	public function filters()
	{
		return array(
			array(
				'COutputCache',
				'duration'=> 30*60, // 30 минут
				'varyByParam'=>array('slug'),
				'varyByExpression' => '(int)Yii::app()->user->isGuest . t::iso()',
			),
		);
	}

	public function actionIndex($slug){
		$post = Pages::model()->findByAttributes(array('slug' => $slug));

		if ($post){
			$this->active_page = '/page/'.$slug;
			$this->metaDescription = $post->description;
			$this->metaKeywords    = $post->keyword;
			$this->pageTitle        = $post->name;

			$criteria = new CDbCriteria();
			$criteria->order = 'id DESC';
			$criteria->limit = 4;
			$news = RNews::model()->news()->current()->findAll($criteria);

			$this->render('index', array(
				'post' => $post,
				'news' => $news
			));
		} else throw new CHttpException(404);
	}
}