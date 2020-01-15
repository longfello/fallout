<?php
$formatter = new IntlDateFormatter(t::iso(), IntlDateFormatter::FULL, IntlDateFormatter::FULL);
$formatter->setPattern('d MMMM');
?>
<div class="form-play-go">
  <div class="form-body">
    <div class="form-body__middle">
      <button class="switch-tab btn btn-go-play" data-href="#fp-tab-register"><?= t::get('Начать игру'); ?></button>
    </div>
  </div>
</div>
<section class="wrapper-page" itemscope itemtype="http://schema.org/Article">
  <h1 class="title-page text-uppercase"><span itemprop="name headline"><?php echo $post->title; ?></span><div class="title-date"><?php echo $formatter->format(new DateTime($post->date)); ?></div></h1>
  <span style="display: none;" itemprop="datePublished"><?= $post->date; ?></span>
  <span style="display: none;"><img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $post->getImg()), 380, 250); ?>" itemprop="image" alt="<?php echo $post->title; ?> | Revival Online"></span>
  <div class="body-page row">


    <div class="articles-inner" itemprop="articleBody">

      <?php
      $article_title = $post->title;
      $article_content = $post->news;

      $dom = new DOMDocument;
      $dom->loadHTML($article_content);
      $images = $dom->getElementsByTagName('img');

      for($i=1;$i<=$images->length; $i++) {
        $node = $images->item($i-1);
        $image_alt = ($node->hasAttribute('alt'))?$node->getAttribute('alt'):'';
        $alt = (($image_alt=='')?$article_title." [".$i."]":$image_alt)." | Revival Online";
        $alt = str_replace(['"', "'"], '', $alt);
        $node->setAttribute('alt', $alt);
      }

      echo $dom->saveHTML();
      ?>
      
      <br>
      <hr>
      <div id="disqus_thread"></div>
      <script>
        <?php
        $d_lang = t::iso();
        if ($d_lang=='es') {
          $d_lang = "es_ES";
        }
        ?>

        var disqus_config = function () {
          this.page.url = document.location.href;  // Replace PAGE_URL with your page's canonical URL variable
          this.language = "<?= $d_lang; ?>";
        };

        (function() { // DON'T EDIT BELOW THIS LINE
          var d = document, s = d.createElement('script');
          s.src = '//rev-online-biz.disqus.com/embed.js';
          s.setAttribute('data-timestamp', +new Date());
          (d.head || d.body).appendChild(s);
        })();
      </script>
      <noscript><?= t::get('Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.'); ?></a></noscript>
    </div>


    <div class="row">
      <?php foreach($articles as $one) { ?>
        <div class="col-xs-6 post-item row">
          <div class="post-item__inner">
            <div class="post-item__img">
              <a href="<?php echo $one->getURL(); ?>" class="post-item__img-link"> <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 380, 250); ?>" alt="<?= addslashes($one->getML('title')); ?> | Revival Online"> </a>
            </div>
            <div class="post-item__title">
              <a href="<?php echo $one->getURL(); ?>" class="post-item__title-link"><?= RText::truncateTextCount($one->getML('title'), 58, '...') ?></a>
            </div> <div class="post-item__date"><?php echo $formatter->format(new DateTime($one->date)); ?></div>
            <div class="post-item__text"><?= RText::truncateTextCount($one->getML('news'), 80, '...') ?></div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

</section>
