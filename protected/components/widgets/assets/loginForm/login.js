var __LoginForm = function(){
  this.config = {
    sel : {
      wrapper: '.form-play',
      gender: '.gender-select a',
      genderInput: '.gender-input',
      close: 'span.close-form',
      tab: {
        links: '.fp-tabs-links a',
        content: '.fp-tabs > div'
      },
      form: {
        login: '#fp-tab-login form',
        register: '#fp-tab-register form',
        forgot: '#fp-tab-forgot form'
      }
    }
  };
  this.obj = {
    wrapper : null,
    form: {
      login: null,
      register: null,
      forgot: null
    }
  };
  this.init = function(){
    var wrapper = $(self.config.sel.wrapper);
    self.obj.wrapper = wrapper;
    self.obj.form.login = $(self.config.sel.form.login, wrapper);
    self.obj.form.register = $(self.config.sel.form.register, wrapper);
    self.obj.form.forgot = $(self.config.sel.form.forgot, wrapper);

    $(self.config.sel.tab.links, wrapper).on('click', self.event.click.tab);
    $('.switch-tab').on('click', self.event.click.tab);
    $(self.config.sel.gender, wrapper).on('click', self.event.click.gender);
    $(self.config.sel.close, wrapper).on('click', self.event.click.close);
    $(self.obj.form.login).on('submit', self.event.submit.login);
    $(self.obj.form.register).on('submit', self.event.submit.register);
    $(self.obj.form.forgot).on('submit', self.event.submit.forgot);

    if (!self.obj.wrapper.is(':visible')){
      self.hide();
    }
  };
  this.show = function(){
    self.obj.wrapper.show();
    $('.header').removeClass('withoutLoginBox hidden');
  };
  this.hide = function(){
    self.obj.wrapper.hide();
    $('.header').addClass('withoutLoginBox');
  };
  this.event = {
    click: {
      tab: function(e){
        e.preventDefault();
        if (!$(self.obj.wrapper).is(":visible")) {
          self.show();
        }
        $(self.obj.wrapper).removeClass('hidden');
        $(self.config.sel.tab.content, self.obj.wrapper).addClass('hidden');
        $($(this).data('href'), self.obj.wrapper).removeClass('hidden');
        $(self.config.sel.tab.links, self.obj.wrapper).removeClass('active');
        $($(this).data('href')+'-link').addClass('active');
        $('.form-play-go').hide();
        $.scrollTo($($(this).data('href'), self.obj.wrapper).offset().top - 135, 1000);
      },
      gender: function(){
        $(self.config.sel.genderInput, self.obj.wrapper).val($(this).data('value'));
      },
      close: function(e){
        self.hide();
        $(self.obj.wrapper).addClass('hidden');
        $('.form-play-go').show();
        $.scrollTo(0, 1000);
      }
    },
    submit: {
      login: function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        $.post('/site/login', data, function(response){
          if (response.result) {
            $(self.obj.form.login).replaceWith($('<p class="successful-message"></p>').html(response.massage));
            document.location.href = '/city.php';
          } else {
            $('.error', self.obj.form.login).html(response.massage);
          }
        }, 'json');
      },
      register: function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        $(this).find('.btn-go-play').hide();
        $(this).find('.preloader').show();
        $.post('/site/register', data, function(response){
          $(self.obj.form.register).find('.preloader').hide();
          $(self.obj.form.register).find('.btn-go-play').show();
          if (response.result) {
            $(self.obj.form.register).replaceWith($('<p class="successful-message"></p>').html(response.message));
            document.location.href = '/player/invite';
          } else {
            $('.error', self.obj.form.register).html(response.message);
          }
        }, 'json');
      },
      forgot: function(e){
        e.preventDefault();
        var data = $(this).serializeArray();
        $(this).find('.btn-go-play').hide();
        $(this).find('.preloader').show();
        $.post('/site/recovery', data, function(response){
          $(self.obj.form.forgot).find('.preloader').hide();
          $(self.obj.form.forgot).find('.btn-go-play').show();
          if (response.result) {
            $(self.obj.form.forgot).replaceWith($('<p class="successful-message"></p>').html(response.message));
          } else {
            $('.error', self.obj.form.forgot).html(response.message);
          }
        }, 'json');
      }
    }
  };

  var self = this;
};

var loginForm = new __LoginForm();
$(document).ready(function(){
  loginForm.init();
});
