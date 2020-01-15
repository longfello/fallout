(function($){

  var __pwcostsbox = function(){
    this.cost_id = 0;

    this.init = function(){
      self.cost_id=$('.box-content').data('cost-id');
      self.initEvents();
      self.reload();
    };
    this.initEvents = function(){
      $(document).on('popupAjaxComplete', self.events.popupAjaxComplete);
    };
    this.reInstallHandlers = function(){
      $('.equipmentAutocomplete').autocomplete({
        source: function( request, response ) {
          $.ajax( {
            url: "/admin/api/autocomplete",
            data: {
              category: 'equipment',
              type: $('#PwCostsContentItems_type').val(),
              term: request.term
            },
            success: function( data ) {
              response( data );
            }
          } );
        },
        minLength: 2,
        select: function( event, ui ) {
          return self.addEquipment(ui.item);
        }
      } );
      $(document).on('click', '.equipmentItemsList li a.remove', self.events.click.removeItem);
      $('#PwCostsContentItems_type').on('change', self.events.change.itemType);
      self.recalcItems();
    };
    this.addEquipment = function(item){
      $('.equipmentItemsList').append("<li data-id='"+item.id+"'>"+item.label+"<a class='remove btn btn-danger btn-xs'>X</a></li>");
      $('.equipmentAutocomplete').val('').focus();
      self.recalcItems();
      return false;
    };
    this.clearItems = function(){
      $('.equipmentItemsList li').remove();
      $('#equipmentList').val('');
    };
    this.recalcItems = function(){
      var complex_value = [];
      $('.equipmentItemsList li').each(function(){
        complex_value.push($(this).data('id'));
      });
      $('#equipmentList').val(complex_value.join(','));
      if ($('#PwCostsContentItems_type').val() == 'platinum' || $('#PwCostsContentItems_type').val() == 'gold' ){
        $('.equipmentGroup').hide();
      } else {
        $('.equipmentGroup').show();
      }
    };

    this.reload = function(){
      $('.box-content').load('/admin/PwCostsBox/load?id='+self.cost_id);
    };

    this.events = {
      click: {
        removeItem: function(e){
          e.preventDefault();
          $(this).parents('li').remove();
          self.recalcItems();
        }
      },
      change: {
        itemType: function(e){
          self.clearItems();
          self.recalcItems();
        }
      },
      popupAjaxComplete: function(){
        self.reload();
        self.reInstallHandlers();
      }
    };


    var self = this;
    this.init();
  };

  $(document).ready(function(){
    window.pwcostsbox = new __pwcostsbox();
  });

})(jQuery);