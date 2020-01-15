<?php

class DefaultController extends Controller {
	public $layout          = 'home';
	public $langModel       = t::MODEL_HOME;
	public $metaDescription = '';
	public $loginForm       = false;
	public $bodyClass       = 'home';

	public function actionLink() {
		$model = isset($_GET['model'])?$_GET['model']:false;
		if ($model) {
			/** @var $model ReferalLinks */
			Yii::app()->session->add(ReferalLinks::SESSION_SLUG, $model->id);

			$log = new ReferalUsers();
			/** @var $log ReferalUsers */
			if (Yii::app()->stat && Yii::app()->stat->id){
				$log->player_id = Yii::app()->stat->id;
			}
			$log->action = ReferalUsers::ACTION_OPEN;
			$log->link_id = $model->id;
			$log->save();

			return Yii::app()->request->redirect($model->redirect_url);
		}
	}
}
