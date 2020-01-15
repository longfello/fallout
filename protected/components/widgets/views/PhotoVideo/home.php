<?php
/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 10.05.16
 * Time: 15:02
 */
  Yii::app()->getModule('thumbler');
  Yii::app()->clientScript->registerScriptFile('/js/lazyload.min.js', CClientScript::POS_HEAD);
?>

<div class="video-screen">
  <div class="video-screen-slider">
    <div class="frame" id="basic">
      <ul class="slidee">
        <?php
          $files = glob(Yii::getPathOfAlias('webroot').'/images/home/*.{jpg,png,mp4}', GLOB_BRACE);
          $cur_file = 0;
          foreach ($files as $file){
            $link = str_replace(Yii::getPathOfAlias('webroot'), '', $file);
            $info = pathinfo($file);
            switch ($info['extension']){
              case 'jpg':
              case 'png':
                $cur_file++;
                $rlink = str_replace(Yii::getPathOfAlias('webroot').'/images/', '', $file);
                $preview = Thumbler::getUrl($rlink, 380, 250);
                $full = Thumbler::getUrl($rlink, 640, 480, Thumbler::EFFECT_IN);
                ?>
                  <li><a class="fancybox" rel="group" href="<?=$full?>"><img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" onload="lzld(this)" data-src="<?= $preview ?>" class="news-item-img" alt="<?= t::get('Видео и скриншоты') ?> [<?= $cur_file;?>] | Revival Online"></a></li>
                <?php
                break;
              case 'mp4':
                ?>

                <li><a class="fancybox" rel="group" href="<?=$link?>">
                    <video controls width="100%" height="100%"  class="news-item-img">
                      <source src="<?= $link ?>" type="video/mp4" />
                    </video>
                  </a></li>

                <?php
                break;
            }
          }
        ?>
      </ul>
    </div>
    <button class="latest-news-slider__arrow latest-news-slider__arrow_prev prevPage"></button>
    <button class="latest-news-slider__arrow latest-news-slider__arrow_next nextPage"></button>
  </div>
</div>

<?php

Yii::app()->clientScript->registerScript('PhotoVideo', "
$('.video-screen-slider a.fancybox').fancybox({
  type: 'iframe',
  width: 656,
  height: 496,
  fitToView   : false,
  autoSize    : false,
  margin      : 0,
  padding     : 0,
  scrolling   : 'no',
  arrows      : false,
  iframe      : {
    scrolling : 'no',
	  preload   : true,
	  margin    : 0,  
	  padding   : 0,  
	}
});
", CClientScript::POS_READY);