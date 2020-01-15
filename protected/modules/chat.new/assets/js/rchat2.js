$(document).ready(function() {

    chat.init();

    $('.chat-nav').on('click', 'a', function(event){
        event.preventDefault();
        elem = $(this);
        elem.toggleClass('active');
        var hrefAttr = elem.attr('href');
        if(hrefAttr === "#you-ignore"){
            $(hrefAttr).slideToggle();
        }
        if(hrefAttr === "#show-player"){
            if(!$(hrefAttr).hasClass("open")){
                $(hrefAttr).addClass("open").animate({
                    right: "+=200px"
                }, 300);
                $('.chat-text ').animate({
                    width: "-=200px"
                }, 300);
            } else {
                $(hrefAttr).removeClass("open").animate({
                    right: "-=200px"
                }, 300);
                $('.chat-text ').animate({
                    width: "+=200px"
                }, 300);
            }
        }
    });
    $('.chat-header').on('click', "#showSmileBtn", function(){
        $('.hiddenSmiles').slideToggle();
    });
    $.mCustomScrollbar.defaults.scrollButtons.enable=true;

    $(".chat-container, .wrapper-players-list ul").mCustomScrollbar({
        axis:"y" // vertical and horizontal scrollbar
    });
});

var chat = {

    // data содержит перменные для использования в классах:
    data : {
        lastId 		: 0,
        noActivity	: 0,
        currentUnixTime: 0,
        currentTempTime: 0,
        chatTimeoutId: 0,
        ignoreData: {},
        currentUserData: {}
    },

    // Инициализация
    init: function() {

        $.ajaxSetup({
            test: "666"
        });

        // Используем переменную working для предотвращения
        // множественных отправок формы:
        var working = false;


        var messageField = $('#RChat_message');
        var privateBlock = $('#privateBlock');
        var isPrivate = $('#RChat_to_player');
        var ignore = $('#ignore');


        // Отправляем данные новой строки чата
        $('#chatForm').submit(function () {


            if (chat.currentUserData.chatban) {

                alert(chat.render('ban', chat.currentUserData.chatban));

                return false;
            }


            if (messageField.val().length == 0) {
                return false;
            }

            var sentMsg = messageField.val();
            messageField.val('');


            if (isPrivate.is(':checked')) {

                var isIgnore = false;

                if (chat.ignoreData.myIgnore || chat.ignoreData.userIgnore) {
                    chat.addIgnoreMessage();
                    return false;
                }

                sentMsg = chat.deleteNicks(sentMsg);
            }


            // Отправим ajax post-запрос
            $.post('/chat/ajax/SubmitForm', $(this).serialize() + '&RChat[message]=' + encodeURIComponent(sentMsg), function (r) {
                working = false;

                privateBlock.hide();
                isPrivate.removeAttr('checked');

                chat.getChats();
            }, 'json');

            messageField.focus();


            return false;
        });


        // Добавление смайлика в поле ввода
        $('.smiles img').click(function () {
            var currentVal = messageField.val();
            messageField.val(currentVal + $(this).attr('alt') + ' ');

            messageField.focus().caret(-1);
        });


        // Показать скрытые смайлы
        var mainSmiles = $('#mainSmiles');
        var hiddenSmiles = $('#hiddenSmiles');
        $('#showSmileBtn').click(function () {
            if ($(this).hasClass('opened')) {
                hiddenSmiles.slideUp();
                mainSmiles.slideDown();
            } else {
                mainSmiles.slideUp();
                hiddenSmiles.slideDown();
            }

            $(this).toggleClass('opened')
        });


        // Выбор ника для отправки сообщения
        $('#rchat').on('click', '.nick', function () {
            var currentVal = messageField.val();
            messageField.val(currentVal + '[b]' + $(this).text() + '[/b], ');

            isPrivate.val($(this).data('user-id'));
            ignore.val($(this).data('user-id'));


            // Показать чекбокс для пометки личного сообщения
            privateBlock.show();
            chat.setCursorEnd();

            // Проверить: находится ли игрок в игноре
            chat.checkIgnore();
        });


        // Подсветка ника на всей странице чата
        $('#rchat').on({
            'mouseenter': function () {
                $('.u' + $(this).data('user-id')).addClass('nickHover');
            },
            'mouseout': function () {
                $('.u' + $(this).data('user-id')).removeClass('nickHover');
            }
        }, '.nick');


        // Добавить/убрать игрока в игнор
        $('#ignore').click(function () {
            var checkbox = $(this);
            var data = {
                'add': function () {
                    return checkbox.prop('checked') ? 1 : 0
                },
                'playerId': function () {
                    return checkbox.val()
                }
            }

            $.post('/chat/ajax/ignore', data, function (r) {

                chat.getIgnorePlayers();
                chat.getListIgnorePlayers();
                chat.checkIgnore();

            });
        });


        // Показать список игроков, которые находятся в игнор-листе
        $('#rchat').on('click', '#ignoreInfo', function () {
            chat.getListIgnorePlayers();
        })


        // Убрать игнор с игрока в игнор-листе
        $('#rchat').on('click', '.ignoreLine input', function () {
            var block = $(this).parent();
            var playerId = {'playerId': $(this).val()}

            $.post('/chat/ajax/DeleteIgnore', playerId, function (r) {

                block.remove();
                chat.getIgnorePlayers();

                chat.getChats();
            });
        });


        // Удаление сообщений админом или модером
        $('#rchat').on('click', '.deleteMsg', function () {

            if (!confirm(rchatLang.confirmDelete)) {
                return false;
            }

            var chatLine = $(this).parent();


            $.post('/chat/ajax/DeleteMessage', 'chat-id=' + chatLine.data('chat-id'), function () {
                chatLine.remove();
            });
        });


        // Самовыполняющиеся функции таймаута
        chat.autoGetCurrentUser();

        (function getUsersTimeoutFunction() {
            chat.getUsers(getUsersTimeoutFunction);
        })();

        chat.getIgnorePlayers();
        $('body').on({
            chat_reload_users_list: function (e, data) {
                if (data.pause) {
                    setTimeout(function(){
                        chat.getUsers();
                    }, data.pause * 1000);
                } else {
                    chat.getUsers();
                }
            },
            chat_reload_chat: function () {
                chat.getChats();
            },
            chat_add_text: function (e, data) {
                console.log(data);
                chat.addChatLine(data);
            },
            rpl_subscribe: function(){
                rpl.subscribe('cmd_chat');
                rpl.subscribe('cmd_chat_'+rpl.uid);
            }
        });

        $( window ).unload(function() {
            $.ajax({
                type: 'POST',
                url: '/chat/ajax/unload',
                success: function(){ return true; },
                async:false
            });
        });
    },
    // Метод render генерирует разметку HTML,
    // которая нужна для других методов
    render: function (template, params) {

        var deleteMsgIcon = function() {
            if (chat.currentUserData.rank == 'Админ' || chat.currentUserData.rank == 'Модер' || chat.currentUserData.rank == 'Чат-Модер') {
                return '<span class="deleteMsg" title="'+rchatLang.deleteMsg+'">&times; </span>'
            }
            else {
                return '';
            }
        };


        var arr = [];
        switch(template) {
            case 'chatLine':

                arr = [
                    '<div class="chat chat-', params.id, '" data-chat-id="', params.id ,'">',
                    deleteMsgIcon(),
                    '<span class="time">', params.time, '</span> ',
                    '<span class="nick ', params.style, ' u',  params.user_id, '" data-user-id="', params.user_id ,'" title="', params.user_id, '">' , params.user, '</span> ',
                    '&raquo;&raquo; <span class="text">', params.message, '</span></div>'
                ];
                break;

            case 'chatLinePrivate':
                arr = [
                    '<div class="chat chat-', params.id, '" data-chat-id="', params.id ,'">',
                    deleteMsgIcon(),
                    '</span><span class="time">', params.time, '</span> <span class="nick ', params.style, ' u',  params.user_id, '" data-user-id="', params.user_id ,'">' , params.user,
                    '</span> ',
                    '<span class="privateInfo">', '['+rchatLang.privat+' ', params.private_user, ']', '</span> ',
                    '&raquo;&raquo; <span class="text">', params.message, '</span></div>'
                ];
                break;

            case 'user':
                arr = [
                    '<div class="user">',
                    '<div class="user-popup"><a href="/view.php?view=', params.id, '" target="_blank">', params.avatar ,'<div class="user-id">[', params.id, ']</div>','</a></div>',
                    '<span class="clanIcon">', params.rankIcon, '</span> ',
                    '<span class="nick ', params.style, ' u',  params.id, '" data-user-id="', params.id ,'">', params.user, '</span> ',
                    params.clanIcon, ' </div>'
                ];
                break;

            case 'myIgnore':
                arr = ['<div class="ignoreBit">',
                    '<span class="time">', params.time, '</span>',
                    '  	&raquo;&raquo; ',
                    '<span class="msg"> '+ rchatLang.ignore2player +'</span>',
                    '</div>'
            ]
                break;

            case 'userIgnore':
                arr = ['<div class="ignoreBit">',
                    '<span class="time">', params.time, '</span>',
                    '  	&raquo;&raquo; ',
                    '<span class="msg"> ' + rchatLang.ignore2me + '</span>',
                    '</div>'
                ]
                break;


            case 'chatIgnoreLine':
                arr = ['<span class="ignoreLine">',
                    '<input checked="checked" type="checkbox" value="', params.id ,'"> ', params.user,
                    '</span> '
                ]
                break;

            case 'ban':
                arr = [
                    rchatLang.banUntil + ' ' + params + '...'
                ]
                break
        }

        // Единственный метод join для массива выполняется
        // бысстрее, чем множественные слияния строк

        return arr.join('');
    },


    // Метод addChatLine добавляет строку чата на страницу
    addChatLine : function(params) {

        if (params.to_player > 0) {
            var markup = chat.render('chatLinePrivate', params);
        }
        else {
            var markup = chat.render('chatLine', params);
        }
        var exists = $('#chatLineHolder .chat-' + params.id);

        if(exists.length){
            exists.remove();
        }

        // Добавить строку чата
        //var previous = $('#chatLineHolder .chat-' + (params.id - 1));
        //if(previous.length) {
        //    previous.before(markup);
        //}
        //else
            $('#chatLineHolder').prepend(markup);

    },

    // Данный метод запрашивает последнюю запись в чате
    // (начиная с lastID), и добавляет ее на страницу.
    getChats: function() {
        $.get('/chat/ajax/GetChats', {lastId: chat.data.lastId}, function(r) {
            //console.log(r);
            for (var i = 0; i < r.chats.length; i++) {
                chat.addChatLine(r.chats[i]);
            }

            if(r.chats.length) {
                chat.data.lastId = r.chats[r.chats.length - 1].id;
            }

            chat.currentUnixTime = r.currentUnixTime;
            chat.currentTempTime = r.currentTempTime;

        }, 'json')
    },


    // Запрос списка всех пользователей.
    getUsers : function(callback) {
        $.get('/chat/ajax/GetUsers', {}, function(r) {

                var users = [];

                for (var i = 0; i < r.users.length; i++) {
                    if (r.users[i]) {
                        users.push(chat.render('user', r.users[i]));
                    }
                }

                var usersRendered = users.join('');
                // Обновить блок, если данные о пользователях изменились
                if ($('#chatUsers').html() != usersRendered) {

                    $('#chatUsers').html(usersRendered);

                    $('#chatTotal').html(r.total);

                    chat.addUserTooltip();


                    if (r.refresh > 0) {
                        chat.refreshChat();
                    }

                }

            setTimeout(callback, 60000);
        }, 'json');
    },


    // Самовыполняющая функция по получению новых сообщений в чате
    autoGetChats: function() {
        (function getChatsTimeoutFunction(){
            chat.getChats(getChatsTimeoutFunction);

            var nextRequest = 60000;
            chat.chatTimeoutId = setTimeout(getChatsTimeoutFunction, nextRequest);
        })();
    },


    // Самовыполняющаяся функция по получения данных о текущем игроке
    autoGetCurrentUser: function() {
        (function getCurrentUsersTimeoutFunction(){

            $.get('/chat/ajax/GetCurrentUser', {}, function(r) {
                if (!chat.currentUserData) {
                    chat.autoGetChats();
                }
                chat.currentUserData = r;

                if (r.chatban) {
                    $('#ban').html(chat.render('ban', r.chatban));
                }
                else {
                    $('#ban').html('');
                }

            }, 'json');

            setTimeout(getCurrentUsersTimeoutFunction, 30000);
        })();
    },


    // Установка курсора вконце строки
    setCursorEnd: function(input) {
        var input = $('#RChat_message');
        var tmpStr = input.val();
        input.val('');
        input.val(tmpStr);
        input.focus();
        input.val(tmpStr);
    },


    // Проверить: находится ли игрок в игноре
    checkIgnore: function() {
        var checkbox = $('#ignore');
        var data = {
            'playerId': function() {
                return checkbox.val()
            }
        }


        $.post('/chat/ajax/CheckIgnore', data, function(r) {
            chat.ignoreData = r;

            if (r.myIgnore) {
                checkbox.attr('checked', true);
            }
            else {
                checkbox.attr('checked', false);
            }
        }, 'json');
    },


    // Добавить сообщение об игноре
    addIgnoreMessage: function() {
        $.get('/chat/ajax/GetCurrentTime', {}, function(r) {

            var params = {
                'time' : r.time
            }

            if (chat.ignoreData.myIgnore) {
                var markup = chat.render('myIgnore', params);
                $('#chatLineHolder').prepend(markup);
            }
            else if (chat.ignoreData.userIgnore) {
                var markup = chat.render('userIgnore', params);
                $('#chatLineHolder').prepend(markup);
            }

        }, 'json');
    },


    // Получить игроков, которые в игноре
    getIgnorePlayers: function() {
        $.get('/chat/ajax/GetIgnorePlayers', {}, function(r) {

            $('#ignoreCount').html(r.ignorePlayers.length);


            var users = [];
            for (var i = 0; i < r.ignorePlayers.length; i++) {
                users.push(r.ignorePlayers[i].user);
            }

            $('#ignorePlayers').html(users.join(', '));

        }, 'json');
    },


    getListIgnorePlayers: function() {
        $.get('/chat/ajax/GetIgnorePlayers', {}, function(r) {

            var users = [];
            for (var i = 0; i < r.ignorePlayers.length; i++) {
                users.push(chat.render('chatIgnoreLine', r.ignorePlayers[i]));
            }

            $('#ignoreList').html(users.join(''));

        }, 'json');
    },


    refreshChat: function() {
        clearTimeout(chat.chatTimeoutId);

        $('#chatLineHolder').empty();
        chat.data.lastId = 0;

        chat.autoGetChats();
    },


    addUserTooltip: function() {
        $('#chatUsers .user').tooltip({
            track: true,
            tooltipClass: 'chatAvatar',
            position: {
                my: "center top+10"
            },
            content: function () {
                return $(this).prop('title');
            }
        })
    },


    // Удалить ники впереди текста сообщения, если оно личное
    deleteNicks: function(text) {
        return text.replace(/^\[b\].*\[\/b\], /gim, '');
    }
}



