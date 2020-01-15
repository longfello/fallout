$(document).ready(function() {

    var params = {
	   changedEl: "select"
	}
	cuSel(params);
    
    $('.cuselActive').live('click', function() {
        $('.cusel-scroll-wrap').hide();
    })
    
    
    // Вход по клику Enter
    $(window).keyup(function(event) {
        if (event.keyCode == 13) {
            if ($('#login_form').is(':visible')) {
                $('#l_login_btn').click();
            }
            if ($('#registration_form').is(':visible')) {
                $('#r_registration_btn').click();
            }
        }    
    });
    
    
    var back_btn = $('#back_btn');
    var forgot_password_btn = $('#recovery_link');
    var back_registration_btn = $('#back_registration_btn');
    var current_form = null;
       
    
    // Показать форму регистрации
    $('.l_registration_btn').live('click', function() {
        $('.form form').hide();
        $('#registration_form').show();
        $('#r_login_btn').show();
        $('#r_registration_btn').show();
        forgot_password_btn.hide();
        $('#error').hide();
        back_btn.hide();
        $('#error_vk_window').hide();
        $('#information').show();
        block_reposition();
        
        return false;
    });


    if (location.hash == '#registration') {
        $('#l_registration_btn').trigger('click');
    }

    back_registration_btn.click(function() {
        $('#l_registration_btn').trigger('click');
        $(this).hide();
    });

    $('.submit_button_box').click(function() {
        $('#l_registration_btn').trigger('click');
    });
    
    
    // Показать форму авторизации
    $('#r_login_btn').live('click', function() {
        //if ($('#login_form').length > 0) {
                $('#registration_form').hide();
                $('#registration_form').hide();
                $('#r_registration_btn').hide();
                $('#r_login_btn').hide();
                $('#login_form').show();
                $('#l_login_form').show();
                $('#l_registration_form').show();
                forgot_password_btn.show();
                block_reposition();
        //}
        /*else {
            $('#registration_form').hide();
            $('#about').hide();
            $('#preloader').show();
            block_reposition();
            $.ajax({
                type: 'GET',
                url: 'index.php?action=login_form',
                dataType: 'html',
                success: function(data) {
                    $('#preloader').hide();
                    $('#about').show();
                    $('.form form:last').after(data);
                    cuSel(params);
                    forgot_password_btn.hide();
                    block_reposition();
                }
                
            });
        }*/
        
        return false;
    });
    
    
    // Показать форму восстановления пароля
    $('.recovery_link').live('click', function() {
        $('#login_form').hide();
        $('#r_login_btn').show();
        $('#r_registration_btn').show();
        forgot_password_btn.hide();
        $('#error').hide();
        back_btn.hide();
        
        if ($('#recovery_form').length > 0) {
            $('#recovery_form').show();
            $('#recovery_btn').show();
            $('#recovery_back_btn').show();
            current_form = $('#recovery_form');
            block_reposition();
        }
        else {
            $('#preloader').show();
            block_reposition();
            $.ajax({
                type: 'GET',
                url: 'index.php?action=recovery_form',
                dataType: 'html',
                success: function(data) {
                    $('#preloader').hide();
                    $('.form form:last').after(data);
                    $('#recovery_btn').show();
                    $('#recovery_back_btn').show();
                    block_reposition();
                    current_form = $('#recovery_form:visible');
                }
                
            });
        }
        
        return false;
    });
        
        
    // Обработчик формы регистрации
    $('#r_registration_btn').click(function() {
        current_form = $('#registration_form:visible');
        
        var login = $('input[name="login"]', current_form).val();
        var sex = $('input[name="sex"]', current_form).val();
        var pass = $('input[name="pass"]', current_form).val();
        var mail = $('input[name="mail"]', current_form).val();
        var code = $('input[name="code"]', current_form).val();
              
        $.ajax({
            type: 'POST',
            url: 'index.php?action=registration',
            dataType: 'json',
            data: { login: login, pass: pass, sex: sex, mail: mail, code: code },
            beforeSend: function() {
                current_form.hide();
                $('#r_registration_btn').hide();
                $('#r_login_btn').hide();
                $('#preloader').show();
                $('#about').hide();
                block_reposition();
            },
            success: function(data) {
                $('#preloader').hide();
                if (!data.error) {
                    $('#message').html(data.success);
                    $('#message').show();
                    block_reposition();
                    setTimeout('document.location.href="city.php?anchor=login";', 2500)
                }
                else {
                    //$('#error').html('<div style="font-size: 15px;">' + data.error + '</div>');
                    $('#error').html(data.error);
                    $('#about').hide();
                    console.log(1);
                    back_btn.show();
                    $('#error').show();
                    block_reposition();
                }
            }
            
        });
    });
    
    
    // Обрабочик формы авторизации
    $('#l_login_btn').live('click', function() {
        if ($('#login_form').is(':visible')) {
            current_form = $('#login_form:visible');
            
            var login = $('input[name="user"]', current_form).val();
            var password = $('input[name="pass"]', current_form).val();
            var remember =  $('input[name="remember"]', current_form).prop('checked');
            
            $.ajax({
                type: 'POST',
                url: 'index.php?action=login',
                dataType: 'html',
                data: { login: login, password: password, remember: remember},
                beforeSend: function() {
                    current_form.hide();
                    forgot_password_btn.hide();
                    $('#about').hide();
                    $('#l_login_form').hide();
                    $('#l_registration_form').hide();
                    $('#preloader').show();
                    block_reposition();
                },
                success: function(data) {
                    $('#preloader').hide();
                    if (!$.trim(data)) {
                       window.location.href = 'city.php'; 
                    }
                    else {
                        $('#error').html(data);
                        $('#error').show();
                        back_registration_btn.show();
                        back_btn.show();
                        block_reposition();
                    }
                }
                
            });
        }
        else {
            $('.form form').hide();
            $('#l_login_form').show();   
        }
    });
    
    
    // Обработчик формы восстановления пароля
    $('#recovery_btn').live('click', function() {
        if ($('#recovery_form').is(':visible')) {
            current_form = $('#recovery_form:visible');
            
            var email = $('input[name="email"]').val();
            
            $.ajax({
                type: 'POST',
                url: 'index.php?action=recovery_mail',
                dataType: 'json',
                data: { email: email },
                beforeSend: function() {
                    current_form.hide();
                    //forgot_password_btn.hide();
                    ///$('#l_login_form').hide();
                    //$('#l_registration_form').hide();
                    $('#preloader').show();
                    block_reposition();
                },
                success: function(data) {
                    $('#preloader').hide();
                    if (!data.error) {
                        $('#message').html(data.success);
                        $('#message').show();
                        back_btn.show();
                        block_reposition();
                        //setTimeout('document.location.href="city.php";', 2500)
                    }
                    else {   
                        $('#error').html(data.error);
                        $('#error').show();
                        back_btn.show();
                        block_reposition();
                    }
                }
            });
        }
        else {
            $('.form form').hide();
            $('#l_login_form').show();   
        }
    });
    
    
    
    back_btn.click(function() {
        $('#error').hide();
        $('#message').hide();
        back_registration_btn.hide();
        back_btn.hide();
        current_form.show();
        $('div', current_form).show();
        $('#about').show();
        block_reposition();
    });
    
    $('#recovery_back_btn').live('click', function() {
        forgot_password_btn.show();
        $(this).hide();
        current_form.hide();
        $('#login_form').show();
        block_reposition();
    });
    

    function block_reposition() {
        $('.form').css({
            'marginTop' : ($('.form').children('.block:visible').outerHeight() / 2) * (-1)
        });
        
        //console.log($('.form').children('.block').outerHeight());
        //$('.form').children('.block').css({ 'border': '1px solid #cc0000' });
    }


    /* Image bar */
    $(".group").colorbox({
        rel:'group1',
        onComplete: function() {
            $('#cboxOverlay').css({ 'opacity' : '.75' });

            var top = $('#colorbox').offset().top + $('#colorbox').outerHeight();
            $('#popup_registration_btn')
                .css({
                    'top' : top
                })
                .show();
        },
        onClosed: function() {
            $('#popup_registration_btn').hide();
        }
    });

    $('#popup_registration_btn').live('click', function() {
        $.colorbox.close();
        $(this).hide();
    });


    /* Info box */
    $('#about').click(function() {
        if ($('#info_window').is(':hidden')) {
            $('#info_window').slideDown();
            $('#error_vk_window').slideUp();
            $('#information').slideUp();
        }
        else {
            $('#info_window').slideUp();
            $('#information').slideDown();
        }

        return false;
    });

    $('.close_button').click(function() {
        $('#info_window').slideUp();
        $('#error_vk_window').slideUp();
        $('#information').slideUp();
    });

    var closeButton = $('.close_button');
    closeButton.mouseover(function() {
        $(this).attr('src', 'images/dialog_box/close_btn_hover.png');
    })
    closeButton.mousedown(function() {
        $(this).attr('src', 'images/dialog_box/close_btn_active.png');
    })
    closeButton.mouseout(function() {
        $(this).attr('src', 'images/dialog_box/close_btn_link.png');
    })

    $('.submit_button_box').mouseover(function() {
        $(this).children('.button_action').attr('src', 'images/dialog_box/d_button_hover.png');
    })
    $('.submit_button_box').mousedown(function() {
        $(this).children('.button_action').attr('src', 'images/dialog_box/d_button_pressed.png');
    })
    $('.submit_button_box').mouseout(function() {
        $(this).children('.button_action').attr('src', 'images/dialog_box/d_button_unpressed.png');
    })



}); // End ready
