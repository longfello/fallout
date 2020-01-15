<?php

class FlushController extends Controller
{
  public $layout = '//layouts/column2';
  public $pageTitle = 'Очитка кеша и ресурсов';

  public function actionIndex()
  {
    $this->render('index');
  }


  /**
   * Очистить ресурсы
   */
  public function actionAssets()
  {
    $dirsList = glob(Yii::app()->assetManager->getBasePath() . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
    if (is_array($dirsList)) {
      foreach ($dirsList as $item) {
        RFile::rmDir($item);
      }
    }
	  if (Yii::app()->request->urlReferrer) {
		  $this->redirect(Yii::app()->request->urlReferrer);
	}  else $this->redirect('index');
  }


  /**
   * Очистить кэш
   */
	public function actionCache()
  {
	  Yii::app()->cache->flush();

    if (Yii::app()->request->urlReferrer) {
		  $this->redirect(Yii::app()->request->urlReferrer);
	}  else $this->redirect('index');
  }

	public function actionAll(){
		$dirsList = glob(Yii::app()->assetManager->getBasePath() . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
		if (is_array($dirsList)) {
			foreach ($dirsList as $item) {
				RFile::rmDir($item);
			}
		}
		Yii::app()->cache->flush();
		if (Yii::app()->request->urlReferrer) {
			$this->redirect(Yii::app()->request->urlReferrer);
		}  else $this->redirect('index');
	}

}