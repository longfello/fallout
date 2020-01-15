<?php

/**
 * Class AjaxController
 */

class DefaultController extends CController {
  public $assetsPath    = 'webroot.assets.i';
  public $assetsUrl     = '/assets/i';
  public $defaultImageAlias = 'webroot.images';
  public $defaultImageFile  = 'no_image.png';

  public function actionIndex(){
    $info = Thumbler::getInfoFromUrl();

    $file = Yii::getPathOfAlias($this->defaultImageAlias).'/'.$info->original_name;
    if (!file_exists($file) || !is_readable($file) || !is_file($file)) {
      $info->original_name = $this->defaultImageFile;
    }
    $cachePath = Yii::getPathOfAlias($this->assetsPath).'/'.$info->cache_path;
    if (!is_dir($cachePath)) {
      mkdir($cachePath, 0775, true);
    }

    $thumb = Yii::app()->phpThumb->create(Yii::getPathOfAlias($this->defaultImageAlias).'/'.$info->original_name);
    /** @var $thumb EThumbnail */

    switch($info->effect) {
      case Thumbler::EFFECT_IN:
        $thumb->adaptiveResize($info->x, $info->y);
        break;
      case Thumbler::EFFECT_CROP:
        $thumb->resize($info->x, $info->y);
        break;
    }

    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (30 * 24 * 60 * 60))); // 30 day
//    $thumb->cropFromCenter($info->x, $info->y);
    $thumb->save($cachePath.'/'.$info->cache_filename);
    $thumb->show();
  }
}