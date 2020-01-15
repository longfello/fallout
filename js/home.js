$(document).ready(function(){

  $(document).on('click', "a.burger-button", function(e){
    e.preventDefault();
    $('.header-main-nav').slideToggle().toggleClass('open');
  });
  $(window).load(function(){
    $("img.center").imgCenter();
  });

  $("input, textarea").on('focus',function(){
    $(this).closest("label").find(".field-text").hide();
  });
  $("input, textarea").on('blur',function(){
    if(!$(this).val() > 0) $(this).closest("label").find(".field-text").show();
  });

  $(".form-body label.dropdown li a").on('click', function(e){
    e.preventDefault();
    var sexText = $(this).text();
    $(this).closest("label").find("a.input-form > span:first-child").text(sexText);
  });

  var $slider = $('.frame');
  var $wrap = $slider.parent();
  $slider.sly({
    horizontal: 1,
    itemNav: 'basic',
    smart: 1,
    activateOn: 'click',
    mouseDragging: 1,
    touchDragging: 1,
    releaseSwing: 1,
    startAt: 1,
    scrollBar: $wrap.find('.scrollbar'),
    scrollBy: 1,
    pagesBar: $wrap.find('.pages'),
    activatePageOn: 'click',
    speed: 300,
    elasticBounds: 1,
    easing: 'easeOutExpo',
    dragHandle: 1,
    dynamicHandle: 1,
    clickBar: 1
  });

  $('#my-video').backgroundVideo({
    pauseVideoOnViewLoss: false,
    $videoWrap: $('#video-wrap'),
    $outerWrap: $('#outer-wrap')
  });

  // setTimeout(function(){autoHeight()},10);


    $('.form-body .switch-tab.btn.btn-go-play, .form-body .close-form').on('click', function() {
        if ($('.header').hasClass('withoutLoginBox')) {
            $('.content-wrapper').addClass('opened-form');
        }
        else {
            $('.content-wrapper').removeClass('opened-form');
        }
    });
    //text cutting
    /*
    $(function() {
        $('.post-item__text').each(function() {
            var max = 70;
            var $textCheck = $(this).text();
            var $cutted = $textCheck.substring(0,max)+'...';
            $(this).text($cutted);

        });

    });

    $(function() {
        $('.post-item__title a').each(function() {
            var max = 40;
            var $textCheck = $(this).text();
            var $cutted = $textCheck.substring(0,max)+'...';
            $(this).text($cutted);

        });

    });
     */

});

/*
  function autoHeight(){
    $(".news-item-img").each(function(index, elem){
      var $elem = $(elem),
        $elemH = $elem.height(),
        $elemW = $elem.width();

      if($elemH == $elemW){
        $elem.parent().addClass("cub", function(){

          setTimeout(function(){
            var $elemH1 = $elem.height();
            var mTop = ($elemH1 - 250) / 2;
            $elem.css({"margin-top":-mTop});
          },1);

        });
      }else if($elemH > $elemW){
        $elem.parent().addClass("vertical", function(){

          setTimeout(function(){
            var $elemH1 = $elem.height();
            var mTop = ($elemH1 - 250) / 2;
            $elem.css({"margin-top":-mTop});
          },1);

        });
      }else{
        $elem.parent().addClass("horizontal", function(){

          setTimeout(function(){
            var $elemW3 = $elem.width();
            if($elemW3 < 380){
              $elem.css({
                "width":"100%",
                "height":"auto"
              });
              var mTop = ($elem.height() - 250) / 2;
              $elem.css({"margin-top":-mTop});

            }else{
              var mLeft = ($elemW3 - 380) / 2;
              $elem.css({"margin-left":-mLeft});
            }
          },1);

        });
      }
    });
  }
*/
