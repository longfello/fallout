(function($){
    window.__appearanceEditor = function(data, player, app){
        this.data   = data;
        this.player = player;
        this.app    = app;

        this.init = function(){
          self.initEvents();
          self.initSliders();
          self.initDefault();
          self.initChatAvatar();
          self.reloadLayouts();
          self.initDefault();
          $('.appearance_block').removeClass('hidden');
        };
        this.initEvents = function(){
          $(document).on('click.slider', 'a.next-slide', self.events.click.next_slide);
          $(document).on('click.slider', 'a.prev-slide', self.events.click.prev_slide);
          $('#set_appearance_button').on('click', self.events.click.save);
          $('.appearance_block form').on('submit', self.events.submit);
        };
        this.initChatAvatar = function(){
          $.get('/ajax_set_appearance.php', {curImage: $('#chat_avatar').data('avatar-id')}, function(r) {
            var imageUrl = '/images/chat/avatars/';

            var curImageBlock = $('#chat_avatar');
            var curNumberBlock = $('#chat_avatar_number');
            var curInput = $('#input_avatar_pic');

            var curNumber = parseInt(r.current);
            var totalImage = r.total;

            // Установить текущий номер картинки
            curNumberBlock.text(curNumber);


            // Стрелки
            $('#choose_box').children('.arrow').click(function() {
              switch ($(this).data('arrow')) {
                case 'left':
                  if (curNumber == 1) {
                    curNumber = totalImage;
                  }
                  else {
                    --curNumber;
                  }
                  break;
                case 'right':
                  if (curNumber == totalImage) {
                    curNumber = 1;
                  }
                  else {
                    ++curNumber;
                  }
                  break;
              }

              curInput.val(r.list[curNumber].avatar_id);
              curNumberBlock.text(curNumber);
              curImageBlock.attr('src', imageUrl + r.list[curNumber].image);
            });
          }, 'json');
        };
        this.initSliders = function(){
          $('.slidewrapper').each(function(){
            self.initSlider(this);
          });
        };
        this.initSlider = function(slider){
            $(slider).data('current', 0).css('width', 64*$('li', slider).size()+'px');
            var i=0;
            $('li', slider).each(function(){
              $(this).removeAttr('class').addClass('slide-'+i).data('no', i);
              if (!$(slider).hasClass('custom')){
                $(this).html(i);
              }
              i++;
            });
            self.setSlide($(slider).parents('.layout'), 0);
        };
        this.setSlide = function(slider, slide){
          $('.slidewrapper', slider).animate({left: -slide*64},300).data('current',slide);
          $('li', slider).removeClass('active');
          $('.slide-'+slide, slider).addClass('active');
        };
        this.loadLayout = function(layout){
          var layoutEl = $('.layout-'+layout);
          $.ajax('/player/layout',{
              data: {
              layout: layout,
              race: $('li.active', '.layout-race').data('id'),
              gender: $('li.active', '.layout-gender').data('id'),
            },
            async: false,
            success: function(data){
                if (data) {
                  $(layoutEl).show();
                  $('.layout-preview-'+$(layoutEl).data('layout')).show();
                  $('ul', layoutEl).html(data);
                  self.initSlider($('.slidewrapper', layoutEl));
                  self.reDraw(layout);
                } else {
                  $(layoutEl).hide();
                  $('.layout-preview-'+$(layoutEl).data('layout')).hide();
                }
              }
            }
          );
        };
        this.reDraw = function(layoutId){
          $('.layout-preview-'+layoutId).attr('src', $('.layout-'+layoutId+' .active').data('url'));
        };
        this.reloadLayouts = function(){
          $('.layout-nested').each(function(){
            self.loadLayout($(this).data('layout'));
          });
        };
        this.initDefault = function(){
          $('.layout').each(function(){
            var slider = this;
            var current = $(this).data('current');
            var no = $('li[data-id='+current+']', this);
            if (no && $(no).data('no')) {
              self.setSlide(slider, $(no).data('no'));
              self.reDraw($(slider).data('layout'));
            }
          });
        };

        this.events = {
          submit: function(e){
            var content = '';
            $('.layout').each(function(){
              var layoutName = $(this).data('layout');
              var layoutValue = $('li.active', this).data('id');
              content += "<input type='hidden' name='layout["+layoutName+"]' value='"+layoutValue+"'>";
            });
            $(this).append(content);
          },
          click: {
            save: function(e){
              e.preventDefault();
              var content = $(this).data('message');
              $('.layout').each(function(){
                var layoutName = $(this).data('layout');
                var layoutValue = $('li.active', this).data('id');
                content += "<input type='hidden' name='layout["+layoutName+"]' value='"+layoutValue+"'>";
              });

              dialog(content, '/player/saveLayout', e);
            },
            next_slide: function(e){
              e.preventDefault();
              var layout = $(this).parents('.layout');
              if (!$(layout).hasClass('disabled')){
                var currentSlide=parseInt($('.slidewrapper', layout).data('current'));
                currentSlide++;
                if(currentSlide>=$('.slidewrapper li', layout).size()){
                  currentSlide = 0;
                }

                self.setSlide(layout, currentSlide);
                self.events.change.slider(layout);
              } else {
                if ($(layout).hasClass('layout-race')){
                  $('body').trigger('popup', {
                    title: '',
                    text: $(layout).data('message')
                  });
                }
              }
            },
            prev_slide: function(e){
              e.preventDefault();
              var layout = $(this).parents('.layout');
              if (!$(layout).hasClass('disabled')) {
                var currentSlide = parseInt($('.slidewrapper', layout).data('current'));
                currentSlide--;
                if (currentSlide < 0) {
                  currentSlide = $('.slidewrapper li', layout).size() - 1;
                }
                self.setSlide(layout, currentSlide);
                self.events.change.slider(layout);
              }
            }
          },
          change: {
            slider: function(slider){
              if (!$(slider).hasClass('layout-nested')){
                self.reloadLayouts();
              } else {
                self.reDraw($(slider).data('layout'));
              }
            }
          }
        };

        var self = this;
        this.init();
    };
})(jQuery);
