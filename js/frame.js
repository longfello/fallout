$(window).load(function() {
    // Подсветка лампочки в горизонтальном меню на текущей странице
    $('#horizontal_menu a, #player_button a').each(function() {
        var currentUrl = '/' + $(this).attr('href');
        if (currentUrl == location.pathname) {
            currentImg = $(this).children('img').attr('src');
            newUrl = currentImg.replace('off', 'on');
            $(this).children('img').attr('src', newUrl);
        }
        if (location.pathname == '/perk.php') {
            currentImg = $('#player_button a img').attr('src');
            newUrl = currentImg.replace('off', 'on');
            $('#player_button a img').attr('src', newUrl);
        }
    });


    
    $('.menu a img').each(function() {
        var path = $(this).attr('src');
        $(this).mouseover(function() {
            var path = $(this).attr('src');
            var newPath = path.replace('normal', 'highlight');
            $(this).attr('src', newPath);
        });
        $(this).mouseout(function() {
            var path = $(this).attr('src');
            var newPath = path.replace('highlight', 'normal');
            $(this).attr('src', newPath);
        });
        $(this).mousedown(function() {
            var path = $(this).attr('src');
            var newPath = path.replace('highlight', 'pushed');
            $(this).attr('src', newPath);
        });
    });

    /*
    $('#player_button a').hover(function() {
          console.log('hover');
          $('#support_player').slideDown();
          $('img', this).attr('src', $('img', this).attr('src').replace('up', 'down'));
        });
    $('#support_player').hover(function(){}, function() {
          console.log('unhover');
          $('#support_player').slideUp();
          $('#player_button a img').attr('src', $('#player_button a img').attr('src').replace('down', 'up'));
        }
    );
    */
    $('#player_button a').off('click.menu').on('click.menu', function(e) {
        e.preventDefault();
        if (!$('#support_player').hasClass('opened')) {
            $('#support_player').slideDown();
            $('img', this).attr('src', $('img', this).attr('src').replace('up', 'down'));
            $('#support_player').addClass('opened')
        } else {
            $('#support_player').slideUp();
            $('img', this).attr('src', $('img', this).attr('src').replace('down', 'up'));
            $('#support_player').removeClass('opened')
        }

    });

    $('#support_player').hover(function(){}, function() {
            console.log('unhover');
            $('#support_player').slideUp();
            $('#player_button a img').attr('src', $('#player_button a img').attr('src').replace('down', 'up'));
            $('#support_player').removeClass('opened')
        }
    );


    // Fix высоты окна на странице карты города
    if ((location.pathname == '/city.php') || (location.pathname == '/')) {
        $('#inner_content').css({ 'minHeight' : '534px' });
    }
    
    // UI Window
   function createDialogNode(content) {
        if (!$('#dialog').length) {
            $('body').append('<div id="dialog">' + content + '</div>');
        }
        $('#dialog').html(content);
   }
    
    window.alertUI = function(content, url, event) {
        createDialogNode(content);
        
        $('#dialog').dialog({
    		buttons: {
    			'Ок': function() { 
    				$(this).dialog('close');
    			}
            }
        });
        
        return false;
    }
    
    window.dialog = function(content, url, event) {
        var submitUrl;
        var content = content;
    		// если передан id формы
        if (url.search('formmsg') >= 0) {
    		content += '<form method=post action=""></form>';
            submitUrl = '#' + url;
            
    	} else {
    		// если передан url
          content = '<form method=post action="' +url+ '">'+content+'</form>';
            submitUrl = '#dialog form';
    	}
        
        createDialogNode(content);

        var buttons = {};
        buttons[lang_i18n.yes] = function() {
            $(submitUrl).submit();
        };
        buttons[lang_i18n.no] = function() {
            $(this).dialog('close');
            $(this).remove();
        };

          
        $('#dialog').dialog({
            buttons: buttons,
            close: function() {
                $(this).remove();
            }
        });
        
        return false;
    };
    

    if (location.pathname != '/chat'){
        $.getJSON('/ajax_first_login.php', {'action' : 'get_status'}, function(data) {
            if (location.pathname == '/train.php' || location.pathname == '/deizi.php' || location.pathname == '/train.php') {
                $.get('/ajax_first_login.php', {'action' : 'turn_off_window'});
            }
            else {
                if (location.pathname.indexOf('/player/invite') === -1 && data) {
                    createDialogNode(data.content);
                    if (data.type == 'first') {
                        var btn_gtc = {};
                        btn_gtc[lang_i18n.goto_training_camp] = function() {
                            close_train_window();
                        };
                        $('#dialog').dialog({
                            buttons: btn_gtc,
                            close: function() {
                                $.get('/ajax_first_login.php', {'action' : 'turn_off_window'});
                                //$(this).dialog('close');
                            },
                            width: 450,
                            title: lang_i18n.training_camp_title
                        });
                    }
                    else if (data.type == 'second') {
                        $('#dialog').dialog({
                            close: function() {
                                $.get('/ajax_first_login.php', {'action' : 'turn_off_window'});
                                //$(this).dialog('close');
                            },
                            width: 450,
                            title: lang_i18n.battle_or_work
                        });
                    }
                }
            }

            show_bonus_window();
        });
    }
    
    
    
    if ($('#train_window_link').length) {
        $('#train_window_link').live('click', function() {
            close_train_window();
            return false;
        });
    }
    
    function close_train_window() {
        $.get('/ajax_first_login.php', {'action' : 'turn_off_window'}, function() {
            location.href = '/train.php';
        });  
    }
    
    
    // Кнопка "Наверх"
    $(window).scroll(function() {
        if ($(this).scrollTop() > 0 && location.pathname != '/city.php') {
            $('#scroller').fadeIn();
        } 
        else {
            $('#scroller').fadeOut();
        }
    });

    $('#scroller').click(function() {
        $('body,html').animate({scrollTop: 0}, 400); 
        return false;
    });
    
    
    /* Клановая дипломатия */
    var clan_name = $('#clan_name').text();
    var clan_id = $('#clan_id').text();
    var owner_name = $('#owner_name').text();
    var owner_id = $('#owner_id').text();
    
    $('#union_sbmt').click(function() {
        var msg = lang_i18n.aureallypeace + ' ' + clan_name + ' [' + clan_id + '], '+lang_i18n.clanhead+' ' + owner_name + ' [' + owner_id + ']?';
        dialog(msg, 'formmsg_union');
        
        return false;
    });
    
    $('#war_sbmt').click(function() {
        var msg = lang_i18n.aureallywar + ' ' + clan_name + ' [' + clan_id + '], '+lang_i18n.clanhead+' ' + owner_name + ' [' + owner_id + ']?';
        dialog(msg, 'formmsg_war');
        
        return false;
    });
    
    
    $('.union_accept_sbmt').click(function() {
        var id = $(this).attr('id').replace('union_accept_id_', '');
        var msg = $('#union_accept_msg_' + id).text();
        dialog(msg, 'formmsg_union_accept_' + id);
        
        return false;
    });
    
    $('.union_cancel_sbmt').click(function() {
        var id = $(this).attr('id').replace('union_cancel_id_', '');
        var msg = $('#union_cancel_msg_' + id).text();
        dialog(msg, 'formmsg_union_cancel_' + id);
        
        return false;
    });
    

    $('.union_clan').click(function() {
        var url = $(this).attr('href');
        var id = $(this).attr('id').replace('union_btn_', '');
        var user_name = $('.user_name_' + id).text();
        var clan_name = $('.clan_name_' + id).attr('alt');
        dialog(lang_i18n.uattack+' ' + user_name + ' [' + id + '], '+ lang_i18n.whichinpeaceclan +' "' + clan_name + '". '+lang_i18n.peaceclanausure, url);
        return false;
    });


    /* Блок помощи новичку */
    $('.beginner_helper_arrow').click(function() {
        if ($('.beginner_helper_content').css('overflow') == 'hidden') {
            $('.beginner_helper_content').switchClass('hide_beginner_helper_msg', 'show_beginner_helper_msg', 400);
        }
        else {
            $('.beginner_helper_content').switchClass('show_beginner_helper_msg', 'hide_beginner_helper_msg', 400);
        }
    });


    /* Логирование */
    if ($('#logging').length > 0) {
        var timeout = parseInt($('#logging').text()) * 1000;
        setInterval(add_logging, timeout);
    }

    function add_logging() {
        var table_data = null;
        $.ajax({
            url: 'ajax_logging.php',
            dataType: 'json',
            type: "POST",
            contentType: "application/json; charset=utf-8",
            success: function(data){
                $.each(data, function(key, val) {
                    table_data += '<tr>';
                    for (var i = 0, c = val.length; i < c; i++ ) {
                        table_data += '<td>' + val[i] + '</td>';
                    }
                    table_data += '</tr>';
                });

                $('#antibot tbody').html(table_data);
            }
        });
    }


    /* gbattle.php */
    $('#gbattle_type').change(function() {
        if (this.value == 'W' && ($('select[name=clan_war_list] option').length > 0)) {
            $('#clan_war_list').show();
            $('.clan_gbattle_off').hide();
            $('select[name="bet_type"] option').hide();
            $('select[name="bet_type"] option[value="free"]').show();
            $('select[name="bet_type"] option[value="free"]').attr('selected', true);
            $('input[name="lvlmin"]').val(1);
            $('input[name="lvlmax"]').val(999);
        }
        else {
            $('#clan_war_list').hide();
            $('.clan_gbattle_off').show();
            $('select[name="bet_type"] option').show();
        }
    });


    /* Показ уникальных предметов на странице stat.php и view.php */
    $('#show_all_unique_items').click(function() {
        $('.unique_item_hidden').show(500);
        $(this).hide();

        return false;
    })


    // Показать созданные битвы по клику на спойлер
    $('.gbattle_block .title').click(function() {
        var cur_block = $(this).next();
        if (cur_block.is(':hidden')) {
            cur_block.show();
        }
        else {
            cur_block.hide();
        }
    });


    /* Вывод бонусов во всплывающем окне */
    function show_bonus_window() {
        /*
        if ($('#dialog').length) {
            var cur_id = '#dialog1';
            var dialog_id = 1;
        }
        else {
        */
            var cur_id = '#dialog';
            var dialog_id = '';
        //}
        $.ajax({
            url: '/ajax_bonus.php',
            data: {'action' : 'get_status'},
            dataType: 'html',
            success: function(data) {
                if (data) {
                    createDialogNode(data);
                  btn_continue = {};
                  btn_continue[lang_i18n.continue] = function() {
                    close_bonus_window(dialog_id);
                  };
                    $(cur_id).dialog({
                        buttons: btn_continue,
                        close: function() {
                            close_bonus_window(dialog_id);
                        },
                        width: 700,
                        title: lang_i18n.bonuses
                    });
                }

                // Кнопка подробнее
                $('#bonus_link_more').click(function() {
                    var dialog_id = '#' + $(this).parent().attr('id');
                    var redirect_url = $(this).attr('href');

                    $.ajax({
                        url: '/ajax_bonus.php',
                        data: {'action' : 'turn_off_window'},
                        success: function() {
                            //location.href = redirect_url;
                        }
                    });
                });
            }
        });
    }

    function close_bonus_window(cur_id) {
        if (cur_id) {
            var cur_id = '#dialog' + cur_id;
        }
        else {
            var cur_id = '#dialog';
        }

        $.ajax({
            url: '/ajax_bonus.php',
            data: {'action' : 'turn_off_window'}
        });
        $(cur_id).dialog('close');
    }


    // Сообщение на странице пещер
    /*if (location.hash == '#time_expired') {
        $.ajax({
            url: 'caves.php',
            dataType: 'text',
            success: function(data){
                createDialogNode('В недрах пещер очень высокая токсичность, время пребывания исчерпано. Следующее посещение возможно через ' + data);

                $('#dialog').dialog({
                    buttons: {
                        'Закрыть': function() {
                            location.hash = '';
                            $(this).dialog('close');

                        }
                    },
                    close: function() {
                        location.hash = '';
                        $(this).dialog('close');
                    },
                    width: 450
                });

            }
        });

    }*/

    //tooltips
    $(document).ready(function($) {
        $('.tooltip-from-element').each(function() {
            var selector = '#' + $(this).data('tooltip-id');
            Tipped.create(this, $(selector)[0], {
                hook:   'bottommiddle',
                offset: { x: 0, y: -15 }
            });

        });
        //extra-hover
        $('.name-loc').hover(function() {
            var getId = $(this).attr('id');
            console.log(getId);
            var testing = $('[data-tooltip-id="' + getId + '"]').addClass('hovered');
            console.log(testing);
            //$('#' + getId).siblings().addClass('hovered');

        }, function() {
            var getId = $(this).attr('id');
            console.log(getId);
            $('[data-tooltip-id=' + getId + ']').removeClass('hovered');

        });

    });



}); // end ready

