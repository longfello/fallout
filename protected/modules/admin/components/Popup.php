<?php
class Popup extends CWidget
{
	public $layout = 'default';

	/**
	 * @return string
	 */
	public function run()
	{
		return $this->render('Popup/'.$this->layout);
	}

	public static function close(){
		header('Content-type: application/json');
		$widget = new self();
		echo CJSON::encode([
			'content' => $widget->render('Popup/__close'),
			'title'   => '',
			'buttons' => ''
		]);
		Yii::app()->end();
	}

	public static function refresh($message = ''){
		if ($message){
			Yii::app()->user->setFlash('success', $message);
		}
		header('Content-type: application/json');
		$widget = new self();
		echo CJSON::encode([
			'content' => $widget->render('Popup/__refresh'),
			'title'   => '',
			'buttons' => ''
		]);
		Yii::app()->end();
	}

	public static function redirect($url, $message = ''){
		if ($message){
			Yii::app()->user->setFlash('success', $message);
		}
		header('Content-type: application/json');
		$widget = new self();
		echo CJSON::encode([
			'content' => $widget->render('Popup/__redirect', ['url' => $url]),
			'title'   => '',
			'buttons' => ''
		]);
		Yii::app()->end();
	}

	public static function btnClose(){
		return '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';
	}
	public static function btnSave($name = 'Сохранить'){
		return '<button type="submit" class="btn btn-primary">'.$name.'</button>';
	}
}