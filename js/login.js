$(document).ready(function() {
    $('#login_btn').click(function() {
        $('#login_box').slideDown(1000);
        $('#recovery_mail').slideUp(1000);
        //$('#registration').slideUp(1000);
        $('#recovery_box_response').slideUp(1000);
        $('#register_box').css('display', '');   
    })
    
    
    // Смена изображения кнопки "Вход" при событии hover
    $('#entry_btn').mouseout(function() {
        $(this).attr('src', 'images/entry_btn_link.png');
    })
    $('#entry_btn').mouseover(function() {
        $(this).attr('src', 'images/entry_btn_hover.png');
    })
    $('#entry_btn').mousedown(function() {
        $(this).attr('src', 'images/entry_btn_active.png');
    })
    
    // Смена изображения кнопки "Восстановить" при событии hover
    $('#recovery_btn').mouseover(function() {
        $(this).attr('src', 'images/further_btn_hover.png');
    })
    $('#recovery_btn').mouseout(function() {
        $(this).attr('src', 'images/further_btn_link.png');
    })
    $('#recovery_btn').mousedown(function() {
        $(this).attr('src', 'images/further_btn_active.png');
    })
    
    $('#response_btn').mouseover(function() {
        $(this).attr('src', 'images/recovery_btn_hover.png');
    })
    $('#response_btn').mouseout(function() {
        $(this).attr('src', 'images/recovery_btn_link.png');
    })
    $('#response_btn').mousedown(function() {
        $(this).attr('src', 'images/recovery_btn_active.png');
    })
    
    
    // Смена изображения кнопки "Далее" при событии hover
    $('#further_recovery_btn').mouseover(function() {
        $(this).attr('src', 'images/further_btn_hover.png');
    })
    $('#further_recovery_btn').mouseout(function() {
        $(this).attr('src', 'images/further_btn_link.png');
    })
    $('#further_recovery_btn').mousedown(function() {
        $(this).attr('src', 'images/further_btn_active.png');
    })
    
    // Смена изображения кнопки "Регистрация" при событии hover
    $('#register_btn').mouseover(function() {
        $(this).attr('src', 'images/register_btn_hover.png');
    })
    $('#register_btn').mouseout(function() {
        $(this).attr('src', 'images/register_btn_link.png');
    })
    $('#register_btn').mousedown(function() {
        $(this).attr('src', 'images/register_btn_active.png');
    })
    
    
    // Вывод формы восстановления пароля
    $('#recover_link a').click(function() {
        $('#login_box').slideUp(1000);
        $('#recovery_mail').slideDown(1000);
    })
    
    // Вывод формы регистрации
    $('#registration_link a').click(function() {
        $('#login_box').slideUp(1000);
        $('#registration').slideDown(1000);
    })
    
    $('#registration_button').click(function() {
        //$('#info_window').slideUp(1000);
        $('#registration').slideDown(1000);
    })
       
    // Fix background Google Chrome
    if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
        $('input').attr('autocomplete', 'off');
    }

    // Fix шрифта в ie
    if ($.browser.msie) {
        $('input').attr('font-family' , 'Consolas!important');
    }
    

    if (window.location.href.indexOf('login') > 0) {
        $('#login_box').slideDown(1000);
    }
    
    if ((window.location.href.indexOf('register') > 0) && (window.location.href.indexOf('error') > 0)) {
        //$('#registration').slideDown(1000);
    }
    
    //if (window.location.href.indexOf('register') > 0) {
    //    $('#info_window').hide();
    //}
    
    if ((window.location.href.indexOf('registeroflog') > 0) && (window.location.href.indexOf('action') > 0)) {
        $('#registration').slideDown(1000);
    }
    
    
    if ((window.location.href.indexOf('mail') > 0) && (window.location.href.indexOf('error') > 0)) {
        $('#recovery_mail').slideDown(1000);
    }
    
    $('#register_box .submit_button_box').click(function() {
        fill_hidden_captcha();
        $('#register_box form').submit();
    });
    
      
    $('#security_question .submit_button_box').click(function() {
        $('.dialog_content_inner form:visible').submit();
    });
    
    var imageDialogDir = 'images/dialog_box/';
    
    $('.submit_button_box').mouseover(function() {
        $(this).children('.button_action').attr('src', imageDialogDir + 'd_button_hover.png');
    })
    
    $('.submit_button_box').mousedown(function() {
        $(this).children('.button_action').attr('src', imageDialogDir + 'd_button_pressed.png');
    })
    
    $('.submit_button_box').mouseout(function() {
        $(this).children('.button_action').attr('src', imageDialogDir + 'd_button_unpressed.png');
    })
    
    var closeButton = $('.close_button');
    closeButton.mouseover(function() {
        $(this).attr('src', imageDialogDir + 'close_btn_hover.png');
    })
    
    closeButton.mousedown(function() {
        $(this).attr('src', imageDialogDir + 'close_btn_active.png');
    })
    
    closeButton.mouseout(function() {
        $(this).attr('src', imageDialogDir + 'close_btn_link.png');
    })
    
    closeButton.click(function() {
        if ($(this).parent().parent().parent().parent().parent().attr('id') == 'register_box') {
            $(this).parent().parent().parent().parent().parent().parent().slideUp(1000);
        }
        else {
            $(this).parent().parent().parent().parent().parent().slideUp(1000);
        }
    })
    
    // Button closing the login error
    var closeButtonErrorLogin = $('.error_login .close_button');
    closeButtonErrorLogin.click(function() {
        document.location.href = '/index.php?anchor=login';
    })
    
    
    $('#error_login .submit_button_box').click(function() {
        $('#error_login').slideUp(1000);
        $('#registration').slideDown(1000);
    });
    
    $('#error_password .submit_button_box').click(function() {
        $('#error_password').slideUp(1000);
        $('#registration').slideDown(1000);
    })
    
    $('#error_email .submit_button_box').click(function() {
        $('#error_email').slideUp(1000);
        $('#registration').slideDown(1000);
    })
     
    
    $(window).keyup(function(event) {
        if (event.keyCode == 13) {
            //if ($('#login_box').is(':visible')) {
                $('form:visible').submit();
            //}
        }    
    });

    
    $('#login_box .submit_button_box').click(function() {
        $('#login_box form').submit();
    });
    
    $('#error_captcha .submit_button_box').click(function() {
        $('#error_captcha form').submit();
    });
    
    $('#error_log .submit_button_box').click(function() {
        $('#error_log form').submit();
    });
    
    $('#complete_registration .submit_button_box').click(function() {
        $('#complete_registration form').submit();
    });
    
    $('#recovery_box_mail .submit_button_box').click(function() {
        $('#recovery_box_mail form').submit();
    });
    
    $('#unknown_user .submit_button_box').click(function() {
        $('#unknown_user form').submit();
    });
    
    $('#wrong_email .submit_button_box').click(function() {
        $('#wrong_email form').submit();
    });
    
    $('#recovery_box_response .submit_button_box').click(function() {
        $('#recovery_box_response form').submit();
    });
    
    $('#complete_recovery .submit_button_box').click(function() {
        $('#complete_recovery form').submit();
    });

    $('#unknown_answer .submit_button_box').click(function() {
        $('#unknown_answer form').submit();
    });
    
    $('#empty_fields .submit_button_box').click(function() {
        $('#empty_fields').slideUp(1000);
        $('#registration').slideDown(1000);
    });
    
    // Draggable and resiziable
    var heightBrowser = $(window).height() -5;
    var widthBrowser = $(window).width() -5;
    var containerWindow = $('#container');
    containerWindow.height(heightBrowser);
    containerWindow.width(widthBrowser);
    
    if ($.browser.mozilla) {
        $('.dialog').draggable({containment: "#container"});
        $('#login_box .dialog').draggable({containment: "#container"});
        $('#registration .dialog').draggable({containment: "#container"});
    }
    else {
        $('.dialog')
            .draggable({
                containment: "#container"
            })
            .resizable({
                containment: "#container",
                minWidth: 235
            });
        $('#login_box .dialog')
            .draggable({
                containment: "#container"
            })
            .resizable({
                containment: "#container",
                minWidth: $('#login_box').width()
            });
        $('#registration .dialog')
            .draggable({
                containment: "#container",
            })
            .resizable({
               containment: "#container",
               minWidth: $('#registration').width()
        });
    }    
    
    // ToolTip
    $('#tooltip_login').tooltip({
        left: -270,
        delay: 0,
        track: true,
        bodyHandler: function() {
            return $("#error_login .dialog_content_inner")[0].innerHTML;
        }
    });
    
    $('#tooltip_pass').tooltip({
        left: -270,
        delay: 0,
        track: true,
        bodyHandler: function() {
            return $("#error_password .dialog_content_inner")[0].innerHTML;
        }
    });
    
    $('#tooltip_email').tooltip({
        left: -270,
        delay: 0,
        track: true,
        bodyHandler: function() {
            return $("#error_email .dialog_content_inner")[0].innerHTML;
        }
    });
    
    $('#tooltip_security_question').tooltip({
        left: -270,
        delay: 0,
        track: true,
        bodyHandler: function() {
            return $("#security_question .dialog_content_inner")[0].innerHTML;
        }
    });
    
    $('#tooltip_referal').tooltip({
        left: -270,
        delay: 0,
        track: true,
        bodyHandler: function() {
            return $("#referal .dialog_content_inner")[0].innerHTML;
        }
    });
    
    $('#captcha').tooltip({
        left: -270,
        delay: 0,
        track: true,
        bodyHandler: function() {
            return $("#tooltip_captcha .dialog_content_inner")[0].innerHTML;
        }
    });    
    
    
    // Cusel
   	var params = {
	   changedEl: "select"
	}
	cuSel(params);

    // Filling hidden captcha
    function fill_hidden_captcha() {
        var source_string = $('.input_right_login').val() +
                            $('.input_right_first_email').val() +
                            $('.input_right_password').val();
        $('#inv_captcha').val(md5(source_string));      
    }
    
}); // End ready



