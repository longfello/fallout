/**
 * Created by miloslawsky on 20.09.16.
 */

(function($){

  var __kchat = function(){
    this.ignoreData = [];
    this.lang = '';
    this.last_id = 0;
    this.sendBlock = false;

    this.sel = {
      wrapper : '.chat-module',
      nav : '.chat-nav',
      navBtn: {
        btnShowPlayer : 'a.btnPlayerList',
        btnIgnore     : 'a.btnIgnore',
        btnShowSmile  : 'img#showSmileBtn',
        btnDel        : '.message .deleteMsg'
      },
      navTab: {
        tabIgnore  : "#you-ignore",
        tabPlayers : "#show-player",
      },
      smileWrapper : '.smileContainer',
      smile : '.smileContainer img',
      chatWrapper : '.chat-text',
      customScroll: ".chat-container, .wrapper-players-list ul",
      playerList : '.wrapper-players-list',
      playerInList : '.wrapper-players-list ul li.user',
      input: '.write-message',
      private: '.private.pointer',
      ignore: '.ignore.pointer'
    };
    
    this.obj = {
      wrapper         : null,
      nav             : null,
      btnPlayersList  : null,
      btnIgnoreList   : null,
      btnShowSmile    : null,
      tabPlayersList  : null,
      tabIgnoreList   : null,
      chat            : null,
      playerList      : null,
      input           : null,
      private         : null,
      ignore          : null
    };

    this.init = function(){
      self.initObj();
      self.initEvents();
      self.initView();

      self.lang = $('html').attr('lang');
      self.last_id = $(self.obj.wrapper).data('last-id');

      $('html, body').animate({
        scrollTop: 75
      }, 2000);
      self.event.resize();
    };
    this.initView = function(){
      $.mCustomScrollbar.defaults.scrollButtons.enable=true;
      $(self.sel.customScroll, self.obj.wrapper).mCustomScrollbar({
        axis:"y",
        scrollInertia: 500,
        mouseWheel:{ scrollAmount: 200 },
        advanced:{
          updateOnContentResize: true
        }
      });
    };

    this.initObj = function(){
      self.obj.wrapper         = $(self.sel.wrapper);
      self.obj.nav             = $(self.sel.nav, self.obj.wrapper);
      self.obj.btnPlayersList  = $(self.sel.navBtn.btnShowPlayer, self.obj.wrapper);
      self.obj.btnIgnoreList   = $(self.sel.navBtn.btnIgnore, self.obj.wrapper);
      self.obj.btnShowSmile    = $(self.sel.navBtn.btnShowSmile, self.obj.wrapper);
      self.obj.tabPlayersList  = $(self.sel.navTab.tabPlayers, self.obj.wrapper);
      self.obj.tabIgnoreList   = $(self.sel.navTab.tabIgnore, self.obj.wrapper);
      self.obj.chat            = $(self.sel.chatWrapper, self.obj.wrapper);
      self.obj.playerList      = $(self.sel.playerList, self.obj.wrapper);
      self.obj.input           = $(self.sel.input, self.obj.wrapper);
      self.obj.private         = $(self.sel.private, self.obj.wrapper);
      self.obj.ignore          = $(self.sel.ignore, self.obj.wrapper);
    };
    this.initEvents = function(){
      $(self.obj.btnPlayersList).on('click', self.event.click.nav.playersList);
      $(self.obj.btnIgnoreList).on('click', self.event.click.nav.ignoreList);
      $(self.obj.btnShowSmile).on('click', self.event.click.nav.showSmile);
      $(self.obj.input).on('keyup focus blur', self.event.checkFilled);
      $(self.obj.ignore).on('change', self.event.click.ignore);
      $(self.sel.smile, self.obj.wrapper).on('click', self.event.click.smile);
      $('form', self.obj.wrapper).on('submit', self.event.submit);
      $(document).on('mouseover', self.sel.playerInList, self.event.hover.playerInList);
      $(document).on('mouseover', '.nick', self.event.hover.nick);
      $(document).on('mouseout', '.nick', self.event.mouseout.nick);
      $(document).on('click', '.nick', self.event.click.nick);
      $(document).on('click', '.ignoreLine input', self.event.click.removeIgnore);
      $(document).on('click', self.sel.navBtn.btnDel, self.event.click.delete);
      $(window).on('resize', self.event.resize);

      $('body').on(self.event.chat);
      $( window ).unload(self.event.unload);
    };
    this.setPrivat = function(id){
      if (id) {
        $('#privateBlock', self.obj.wrapper).show();
        $(self.obj.private).val(id);
        $(self.obj.ignore).val(id);

        $.post('/chat/ajax/CheckIgnore', {'playerId': id }, function(r) {
          self.ignoreData = r;
          if (r.myIgnore) {
            $('.ignore.pointer', self.obj.wrapper).prop('checked', true);
          }
          else {
            $('.ignore.pointer', self.obj.wrapper).prop('checked', false);
          }
        }, 'json');

      } else {
        $('#privateBlock', self.obj.wrapper).hide();
        $(self.obj.private).val(null).prop('checked', false);
        $(self.obj.ignore).val(null);
      }
    };
    this.getIgnorePlayers = function() {
      $.get('/chat/ajax/GetIgnorePlayers', function(r) {
        $('#ignoreCount').html(r.ignorePlayers.length);
        var users = [];
        var list = [];
        for (var i = 0; i < r.ignorePlayers.length; i++) {
          users.push(r.ignorePlayers[i].user);
          list.push('<span class="ignoreLine"><input type="checkbox" value="'+r.ignorePlayers[i].id+'" checked="checked">'+r.ignorePlayers[i].user+'</span>');
        }
        $('#ignorePlayers').html(users.join(', '));
        $('#ignoreList').html(list.join(''));

      }, 'json');
    };
    this.addIgnoreMessage = function() {
      $.get('/chat/ajax/GetCurrentTime', function(r) {
        var time = r.time;
        if (self.ignoreData.myIgnore) {
          self.chat.addLine('<div class="ignoreBit"><span class="time">' + time + '</span>&raquo;&raquo;<span class="msg"> '+ rchatLang.ignore2player +'</span></div>');
        }
        else if (self.ignoreData.userIgnore) {
          self.chat.addLine('<div class="ignoreBit"><span class="time">' + time + '</span>&raquo;&raquo;<span class="msg"> '+ rchatLang.ignore2me +'</span></div>');
        }
      }, 'json');
    };
    this.placeCaretAtEnd = function(el) {
      el.focus();
      if (typeof window.getSelection != "undefined"
        && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
      } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
      }
    };
    this.testLength = function(){
      var text = $(self.obj.input).clone();
      $('br', text).remove();
      return text.html().length;
    };
    this.chat = {
      clear: function(){
        $('ul', self.obj.chat).empty();
      },
      addLine : function(message){
        $('ul', self.obj.chat).prepend($('<li>').addClass('message').html(message));
      },
      deleteNick: function(text, id){
        var div = $('<div>').html(text);
        $('span.nick.u'+id, div).remove();
        return div.html();
      },
      convertToBBCode: function(text){
        text = $('<div>').html(text);
        $('span.nick', text).each(function(){
          var id = $(this).data('user-id');
          $(this, text).replaceWith("[player="+id+"][/player]");
        });
        $('img', text).each(function(){
          $(this, text).replaceWith($(this).attr('alt'));
        });
        return ($(text).html()).replace('&nbsp;', ' ');
      },
      smileList: {
        show: function(){
          self.chat.ignoreList.hide();
          $('.hiddenSmiles').slideDown();
          $('.mainSmiles').slideUp();
          $('.chat-header').addClass('open');
        },
        hide: function(){
          $('.hiddenSmiles').slideUp();
          $('.mainSmiles').slideDown();
          $('.chat-header').removeClass('open');
        },
        toggle: function(){
          if ($('.chat-header').hasClass('open')){
            self.chat.smileList.hide();
          } else {
            self.chat.smileList.show();
          }
        }
      },
      ignoreList: {
        show: function(){
          self.getIgnorePlayers();
          self.chat.smileList.hide();
          $(self.obj.btnIgnoreList).addClass('active');
          $(self.obj.tabIgnoreList).slideDown();
        },
        hide: function(){
          $(self.obj.btnIgnoreList).removeClass('active');
          $(self.obj.tabIgnoreList).slideUp();
        },
        toggle: function(){
          if ($(self.obj.btnIgnoreList).hasClass('active')){
            self.chat.ignoreList.hide();
          } else {
            self.chat.ignoreList.show();
          }
        }
      }
    };

    this.event = {
      chat: {
        chat_load_users_list: function (e, data) {
          if ((data.lang == self.lang)) {
            if (data.list) {
              $(self.sel.customScroll, self.obj.wrapper).mCustomScrollbar("destroy");

              $('ul', self.obj.playerList).html(data.list);
              // $(self.sel.customScroll, self.obj.wrapper).mCustomScrollbar("update");
              self.initView();
            }
          }
        },
        chat_add_text: function (e, data) {
          if ((data.lang == self.lang) && data.text) {
            self.chat.addLine(data.text);
          }
        },
        chat_reload_chat: function (e, data) {
          if (data.lang == self.lang) {
            self.event.chat.chat_load_users_list(e, data);
            var force = (data.force === 1)?true:false;
            var last_id = force ? 0 : self.last_id;
            $.get('/chat/ajax/GetChats', {lastId: last_id}, function(data) {
              if (data.last_id) {
                self.last_id = data.last_id;
              }
              if (data.messages){
                if (force) {
                  self.chat.clear();
                }
                for(var i in data.messages){
                  self.chat.addLine(data.messages[i]);
                }
              }
            }, 'json');
          }
        },
        rpl_subscribe: function(){
          rpl.subscribe('cmd_chat');
          rpl.subscribe('cmd_chat_'+rpl.uid);
        }
      },
      checkFilled: function(e){
        if ($(self.obj.input).is(':focus') || self.testLength()) {
          $(self.obj.input).addClass('filled');
        } else {
          setTimeout(function(){
            if (!$(self.obj.input).is(':focus') && !self.testLength()){
              $(self.obj.input).removeClass('filled');
            }
          }, 500);
        }
        if (e && e.keyCode == 13){
          $('form', self.obj.wrapper).submit();
        }
        return true;
      },
      click : {
        removeIgnore: function(e){
          var block = $(this).parent();
          var playerId = {'playerId': $(this).val()}

          $.post('/chat/ajax/DeleteIgnore', playerId, function (r) {
            block.remove();
            self.getIgnorePlayers();
            self.event.chat.chat_reload_chat(e, {lang: self.lang});
          });
        },
        ignore: function(e){
          self.chat.ignoreList.show();
          var checkbox = $(this);
          var data = {
            'add': function () {
              return checkbox.prop('checked') ? 1 : 0
            },
            'playerId': function () {
              return checkbox.val()
            }
          };

          $.post('/chat/ajax/ignore', data, function (r) {
            self.getIgnorePlayers();
            var data = {
              'playerId': function() {
                return checkbox.val()
              }
            };

            $.post('/chat/ajax/CheckIgnore', data, function(r) {
              self.ignoreData = r;
              if (r.myIgnore) {
                checkbox.attr('checked', true);
              } else {
                checkbox.attr('checked', false);
              }
            }, 'json');
          });
        },
        delete: function(e){
          e.preventDefault();
          if (!confirm(rchatLang.confirmDelete)) {
            return false;
          }
          var chatLine = $(this).parent();
          $.post('/chat/ajax/DeleteMessage', 'chat-id=' + chatLine.data('chat-id'), function () {
            chatLine.parents('.message').remove();
          });
        },
        nick: function(e){
          e.preventDefault();
          self.setPrivat($(this).data('user-id'));
          $(self.obj.input).append('&nbsp;');
          $(self.obj.input).append($(this).clone().attr('contenteditable', 'false'));
          $(self.obj.input).append('&nbsp;');
          self.event.checkFilled();
          $(self.obj.input).focus();
          self.placeCaretAtEnd(document.getElementById('write-message'));

        },
        smile: function(e){
          e.preventDefault();
          $(self.obj.input).append('&nbsp;');
          $(self.obj.input).append($(this).clone().attr('contenteditable', 'false'));
          $(self.obj.input).append('&nbsp;');
          self.event.checkFilled();
          $(self.obj.input).focus();
          self.placeCaretAtEnd(document.getElementById('write-message'));
        },
        nav: {
          playersList: function(e){
            e.preventDefault();
            $(this).toggleClass('active');
            if(!self.obj.tabPlayersList.hasClass("open")){
              self.obj.tabPlayersList.animate({
                right: "+=200px"
              }, 300);
              self.obj.chat.animate({
                width: "-=180px"
              }, 300);
            } else {
              self.obj.tabPlayersList.animate({
                right: "-=200px"
              }, 300);
              self.obj.chat.animate({
                width: "+=180px"
              }, 300);
            }
            self.obj.tabPlayersList.toggleClass("open");
          },
          ignoreList: function(e){
            e.preventDefault();
            self.chat.ignoreList.toggle();
          },
          showSmile: function(e){
            e.preventDefault();
            self.chat.smileList.toggle();
          }
        }
      },
      hover: {
        playerInList: function(){
          var popupHeight = $('.user-popup', this).height();
          var elPosition = $(this).position().top;
          var scrollPosition = parseInt($('.mCSB_container', self.obj.playerList).css('top'));

          var top = -popupHeight / 2;
          if ((elPosition + scrollPosition + top + popupHeight) > $('.mCustomScrollbar', self.obj.playerList).height()) {
            top = top - ((elPosition + scrollPosition + top + popupHeight) - $('.mCustomScrollbar', self.obj.playerList).height());
          }
          if (-top > elPosition + scrollPosition) {
            top = - (elPosition + scrollPosition);
          }

          $('.user-popup', this).css({top: top});
        },
        nick : function(){
          var uclass = '.u' + $(this).data('user-id');
          $(uclass).addClass('nickHover');
        }
      },
      mouseout: {
        nick: function(){
          var uclass = '.u' + $(this).data('user-id');
          $(uclass).removeClass('nickHover');
        }
      },
      resize: function(){
        var h = $( window ).height();
        $('.chat-main, .chat-wrapper').css({height: h - 165});
        $('.chat-container').css({height: h - 285});
        $('.show-player').css({height: h - 295});
      },
      submit: function(e){
        e.preventDefault();
        if (self.sendBlock) return false;
        self.sendBlock = true;

        var ban = $(self.obj.wrapper).data('banned');
        if (ban) {
          alert(rchatLang.banUntil + ' ' + ban + '...');
          return false;
        }

        var text = $(self.obj.input).clone();
        $('br, a, form', text).remove();

        text = text.html();
        if (text.length == 0){
          self.sendBlock = false;
          return false;
        }


        if ($(self.obj.private).is(':checked')) {
          /*
          if (self.ignoreData.myIgnore || self.ignoreData.userIgnore) {
            self.addIgnoreMessage();
            self.sendBlock = false;
            return false;
          }
          */
          text = self.chat.deleteNick(text, $(self.obj.private).val());
        }
        text = self.chat.convertToBBCode(text);

        $('#RChat_message', self.obj.wrapper).val(text);
        var data = $(this).serializeArray();
        $(self.obj.input).empty().focus();
        self.setPrivat(false);
        self.sendBlock = false;

        $.post('/chat/ajax/SubmitForm', data);
        self.setPrivat(false);
      },
      unload: function(){
        $.ajax({
          type: 'POST',
          url: '/chat/ajax/unload',
          success: function(){ return true; },
          async:false
        });
      }
    };

    var self = this;
    $(document).ready(function(){
      self.init();
    });
  };

  window.kchat = new __kchat();
})(jQuery);


