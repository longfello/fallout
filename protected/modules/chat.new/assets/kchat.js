/**
 * Created by miloslawsky on 20.09.16.
 */

(function($){

  var __kchat = function(){

    this.sel = {
      wrapper : '.chat-module',
      nav : '.chat-nav',
      navBtn: {
        btnShowPlayer : 'a.btnPlayerList',
        btnIgnore     : 'a.btnIgnore',
        btnShowSmile  : 'img.showSmileBtn',
      },
      navTab: {
        tabIgnore  : "#you-ignore",
        tabPlayers : "#show-player",
      },
      chatWrapper : '.chat-text',
      customScroll: ".chat-container, .wrapper-players-list ul"
    };
    
    this.obj = {
      wrapper         : null,
      nav             : null,
      btnPlayersList  : null,
      btnIgnoreList   : null,
      btnShowSmile    : null,
      tabPlayersList  : null,
      tabIgnoreList   : null,
      chat            : null
    };

    this.init = function(){
      self.initObj();
      self.initEvents();
      $.mCustomScrollbar.defaults.scrollButtons.enable=true;
      $(self.sel.customScroll, self.obj.wrapper).mCustomScrollbar({
        axis:"y" // vertical scrollbar
      });
    };

    this.initObj = function(){
      self.obj.wrapper         = $(this.sel.wrapper);
      self.obj.nav             = $(this.sel.nav, this.obj.wrapper);
      self.obj.btnPlayersList  = $(this.sel.navBtn.btnShowPlayer, this.obj.wrapper);
      self.obj.btnIgnoreList   = $(this.sel.navBtn.btnIgnore, this.obj.wrapper);
      self.obj.btnShowSmile    = $(this.sel.navBtn.btnShowSmile, this.obj.wrapper);
      self.obj.tabPlayersList  = $(self.sel.navTab.tabPlayers, this.obj.wrapper);
      self.obj.tabIgnoreList   = $(self.sel.navTab.tabIgnore, this.obj.wrapper);
      self.obj.chat            = $(this.sel.chatWrapper, this.obj.wrapper);
    };

    this.initEvents = function(){
      $(self.obj.btnPlayersList).on('click', self.event.click.nav.playersList);
      $(self.obj.btnIgnoreList).on('click', self.event.click.nav.ignoreList);
      $(self.obj.btnShowSmile).on('click', self.event.click.nav.showSmile);
    };

    this.event = {
      click : {
        nav: {
          playersList: function(e){
            e.preventDefault();
            $(this).toggleClass('active');
            if(!self.obj.tabPlayersList.hasClass("open")){
              self.obj.tabPlayersList.animate({
                right: "+=200px"
              }, 300);
              self.obj.chat.animate({
                width: "-=200px"
              }, 300);
            } else {
              self.obj.tabPlayersList.animate({
                right: "-=200px"
              }, 300);
              self.obj.chat.animate({
                width: "+=200px"
              }, 300);
            }
            self.obj.tabPlayersList.toggleClass("open");
          },
          ignoreList: function(e){
            e.preventDefault();
            $(this).toggleClass('active');
            self.obj.tabIgnoreList.slideToggle();
          },
          showSmile: function(e){
            e.preventDefault();
            $('.hiddenSmiles').slideToggle();
            $('.chat-header').addClass('open');
          }
        }
      }
    };

    var self = this;
    $(document).ready(function(){
      self.init();
    });
  };

  window.kchat = new __kchat();

})(jQuery);
