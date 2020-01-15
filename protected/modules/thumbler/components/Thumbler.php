<?php

class Thumbler {
  const EFFECT_CROP = 'crop';
  const EFFECT_IN = 'in';

  const PATH_DELIMITER = '~';

  public static function getUrl($relativeOriginalPath, $x, $y = false, $effect = self::EFFECT_IN){
    return '/assets/i'.self::getRelativeCachePath($relativeOriginalPath, $x, $y, $effect);
  }

  public static function getRelativeCachePath($relativeOriginalPath, $x, $y = false, $effect = self::EFFECT_IN){
    $info = pathinfo($relativeOriginalPath);
    $path = $info['dirname'];
    $file = $info['basename'];

    $usize = $y?"{$x}x{$y}":"{$x}";
    $upath = substr(sha1($path), 0, 1);
    $ufile = substr(sha1($file), 0, 2);
    $fpath = str_replace('/', self::PATH_DELIMITER, $relativeOriginalPath);
    $link = "/{$usize}/{$effect}/{$upath}/{$ufile}/{$fpath}";

    return $link;
  }

  /**
   * @return bool|ThumbInfo
   */
  public static function getInfoFromUrl(){
    $url = Yii::app()->request->url;
    $parts = explode('/', $url);
    if (count($parts) < 5) return false;

    $fpath  = array_pop($parts);
    $ufile  = array_pop($parts);
    $upath  = array_pop($parts);
    $effect = array_pop($parts);
    $usize  = array_pop($parts);

    $x = $y = $usize;
    $sizes = explode('x', $usize);
    if (count($sizes) > 1) {
      $y = array_pop($sizes);
      $x = array_pop($sizes);
    }
    $x = intval($x);
    $y = intval($y);


    $info   = new ThumbInfo();
    $info->original_name = str_replace(self::PATH_DELIMITER, '/', $fpath);
    $info->cache_path    = $usize.'/'.$effect.'/'.$upath.'/'.$ufile;
    $info->cache_filename= $fpath;
    $info->cache_name    = $info->cache_path.'/'.$info->cache_filename;
    $info->effect        = $effect;
    $info->x             = $x;
    $info->y             = $y;

    return $info;
  }
}