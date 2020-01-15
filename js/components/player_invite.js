$(document).ready(function(){
   $("#invite_email").submit(function(e){
       e.preventDefault();
       var form = $(this);
       var fdata = form.blur().serializeArray();
       var action = form.attr('action');
       form.find(".errors").html('');
       $.ajax({
           url: action,
           type: "POST",
           data: fdata,
           dataType: 'json',
           success: function(res) {
               if (res.result==1) {
                   document.location.reload();
               } else {
                   form.find(".errors").html(res.errors);
               }
           }
       })
   });

   $("#check_all_invites").click(function(e){
       e.preventDefault();
       $('#invite_email input[type=checkbox]').prop('checked', true);
   });

    $("#uncheck_all_invites").click(function(e){
        e.preventDefault();
        $('#invite_email input[type=checkbox]').prop('checked', false);
    });
});