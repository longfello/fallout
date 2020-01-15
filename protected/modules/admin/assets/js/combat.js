/**
 * Created by miloslawsky on 19.09.16.
 */

(function($) {
  var __combat = function () {
    this.init = function(){
      self.initEvents();
    };
    this.initEvents = function(){
      $('button.btnStartCombat').on('click', self.events.click.startCombat);
    };
    this.events = {
      click: {
        startCombat: function(e){
          e.preventDefault();

          $('.combat-parameters form').each(function(){
            var data = $(this).serializeArray();
            $.post($(this).attr('action'), data);
          });

          var btn = this;

          $('i, span', btn).toggleClass('hidden');
          $(btn).prop('disabled', function(i, v) { return !v; });

          $('.combat-log').load('/admin/combat/go', function(){
            $('i, span', btn).toggleClass('hidden');
            $(btn).prop('disabled', function(i, v) { return !v; });
          });
        }
      }
    };

    var self = this;
    $(document).ready(function(){
      self.init();
    });
  };

  window.combat = new __combat();
})(jQuery);
