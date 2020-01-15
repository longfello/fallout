$(function() {
        if ($('#citymap').size() > 0){
            var now= new Date(), ampm= 'am', h = now.getHours(), date1='night';
            if ((h >= 6) && (h < 22)){
                date1='day';
            }
            $('img', $('#citymap')).attr('src', "/images/town_"+date1+".jpg");

            $('.city_district_hint').each(function(){

                var image = $(this).data('img');
                if (image) {
                    $(this).data('src', "/images/locations/"+date1+"/"+image);
                }
            });
        }


        $("#run_energy").click(function () {
        $.post('ajax_tatoo.php',
            {
                'action':'regen'
            },function(data) {
                eval(data);
                if (success) {
                    location.reload(true);
                }
            });
    });
});
function on_load() {
    window.status='<?php print "$title - $site_com"; ?>';
    on_load_1();
}
function update_info_ex(hp, max_hp, energy, max_energy, gold, mail, logs, numchat, weight, numchatclans, exp) {
    update_info(hp + "/" + max_hp, energy + "/" + max_energy, gold, weight, exp);

    if(mail > 0)
    {
        document.getElementById("obj_mail_link").href = "/mail.php?view=inbox";
        document.getElementById("obj_mail_img").alt = "Новое письмо!";
        document.getElementById("obj_mail_img").src = "/images/new_mail.gif";
    }
    else
    {
        document.getElementById("obj_mail_link").href = "/mail.php?view=inbox";
        document.getElementById("obj_mail_img").alt = "Почта";
        document.getElementById("obj_mail_img").src = "/images/unread.gif";
    }

    var logButton = $('#log_btn');
    if (logs > 0)
    {
        currentImg = logButton.children('img').attr('src');
        newUrl = currentImg.replace('roff', 'ron');
        logButton.children('img').attr('src', newUrl);
    }

    var elem = document.getElementById("obj_my_chatclans");
    if (elem) {
        var obj = elem.style;
        if( numchatclans > 0 )
        {
            obj.color="darkred";
            obj.fontWeight="bold";
        }
        else
        {
            obj.color="";
            obj.fontWeight="";
        }
    }
}
function update_info(hp, energy, gold, weight, exp){
    document.getElementById("id_hp").innerHTML = hp;
    document.getElementById("id_energy").innerHTML = energy;
    document.getElementById("id_gold").innerHTML = gold;
    document.getElementById("id_weight").innerHTML = weight;
    if (exp) $("#exp_stat").replaceWith(exp);
}
/*
 function update_info2(pohod,tokens,platinum)
 {
 //обновление походных очков, воды и крышечек
 document.getElementById("id_pohod").innerHTML = "Походные очки: " + pohod;
 document.getElementById("id_tokens_platinum").innerHTML = "<img src=images/tokens.png> " + tokens + " / <img src=images/platinum.png> " + platinum;
 }
 */
function update_info2(pohod, tokens, platinum){
    //обновление походных очков, воды и крышечек
    document.getElementById("id_pohod").innerHTML = pohod;
    document.getElementById("id_platinum").innerHTML = platinum;
    document.getElementById("id_tokens").innerHTML = tokens;
}
function on_load_1(){
    on_load_2();
    on_load_3();
}
function on_load_3(){
    window.setInterval("load_info();", 60000); // 60000
}
function load_info(){
    $.getJSON('/ajax_update_stats.php',
        function(data) {
            if (!data) {
                location.href = '/';
            }

            var logs = data.logs || data.numlog;

            update_info_ex(data.hp, data.max_hp, data.energy, data.max_energy, data.gold,
                data.mail, logs, data.numchat, data.weight, data.numchatclans, data.exp)
        });
}
function on_load_2(){}

(function($){
    $(document).ready(function(){

      History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
        var createtime = State.data.createtime;
        var timestamp = new Date().getTime();
        var diff = timestamp - createtime;

        $('#inner_content').css('cursor', 'wait').load(State.url + ' #inner_content', function(){
          $('#inner_content').css('cursor', 'default');
          if(diff<500) {
            load_info();
          }
        });
      });

      $(document).on('click', 'ul.pustosh-actions a, a.direction, a.ajaxify, .control .user_actions a', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        loadContent(url);
      });

    });
})(jQuery);

function  loadContent(url) {
  if (url.indexOf("#") > 0){
    url = url.substring(0, url.indexOf('#'));
  }
  History.pushState({createtime: new Date().getTime()}, $(document).attr('title'), url);
  console.log(url);
}