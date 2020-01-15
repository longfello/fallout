<?php
$formatter = new IntlDateFormatter(t::iso(), IntlDateFormatter::FULL, IntlDateFormatter::FULL);
$formatter->setPattern('d MMMM Y');
?>
<div class="form-play-go">
    <div class="form-body">
        <div class="form-body__middle">
            <button class="switch-tab btn btn-go-play" data-href="#fp-tab-register"><?= t::get('Начать игру'); ?></button>
        </div>
    </div>
</div>
<section class="wrapper-page" itemscope itemtype="http://schema.org/NewsArticle">
    <h1 class="title-page text-uppercase" itemprop="name headline"><?php echo $post->title; ?></h1>
    <span style="display: none;" itemprop="datePublished"><?= $post->date; ?></span>
    <span style="display: none;"><img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $post->getImg()), 380, 250); ?>" itemprop="image" alt="<?php echo $post->title; ?> | Revival Online"></span>
    <div class="body-page row row-article">

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=1101563206557856";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div class="fb-follow" data-href="https://www.facebook.com/revivalon/" data-layout="standard" data-size="large" data-show-faces="true"></div>

        <div class="articles-inner articles-news-page" itemprop="articleBody">

            <?php
            $news_title = $post->title;
            $news_content = $post->news;

            $dom = new DOMDocument;
            $dom->loadHTML($news_content);
            $images = $dom->getElementsByTagName('img');

            for($i=1;$i<=$images->length; $i++) {
                $node = $images->item($i-1);
                $image_alt = ($node->hasAttribute('alt'))?$node->getAttribute('alt'):'';
                $alt = (($image_alt=='')?$news_title." [".$i."]":$image_alt)." | Revival Online";
                $alt = str_replace(['"', "'"], '', $alt);
                $node->setAttribute('alt', $alt);
            }
            
            echo $dom->saveHTML();
            ?>

            <br><br>
            <hr>
            <br><center><i><?= t::get('Поделитесь новостью с друзьями!') ?></i><br>
                <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                <script src="//yastatic.net/share2/share.js"></script>
                <div class="ya-share2" data-services="vkontakte,facebook,gplus,twitter,reddit,tumblr"></div>
                <br>
                <?php if (t::iso()=='ru') { ?>
                    <a href="http://vk.com/revivalonline" style="text-decoration:underline;" rel="nofollow" target='_blank'><img src='/images/news/vkrev.png' alt="<?= t::get('Поделитесь новостью с друзьями!') ?> | Revival Online"></a>
                <?php } ?>
            </center>
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
            <?php foreach($news as $one) { ?>
                <div class="col-xs-6 post-item row">
                    <div class="post-item__inner">
                        <div class="post-item__img">
                            <a href="<?php echo $one->getURL(); ?>" class="post-item__img-link"> <img src="<?php echo Thumbler::getUrl(str_replace('/images/', '', $one->getImg()), 380, 250); ?>" alt="<?= $one->getML('title'); ?> | Revival Online"> </a>
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
