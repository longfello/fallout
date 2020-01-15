(function($){

  var __nkrprices = function(){
    this.event_id = 0;

    this.init = function(){
      self.event_id=$('.price-content').data('event-id');
      self.initEvents();
      self.reload();
    };
    this.initEvents = function(){
      $(document).on('popupAjaxComplete', self.events.popupAjaxComplete);
    };
    this.reload = function(){
      $('.price-content').load('/admin/NkrPrices/load?id='+self.event_id);
    };

    this.events = {
      popupAjaxComplete: function(){
        self.reload();
      }
    };


    var self = this;
    this.init();
  };

  $(document).ready(function(){
    window.nkrprisec = new __nkrprices();
  });

})(jQuery);