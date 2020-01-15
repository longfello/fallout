$(document).ready(function() {
    
    $('#show_all_gifts').click(function() {
        
        $('.hidden_gift').show();
        
        return false;
    });
    
    
    $('#show_all_tattoo').click(function() {
        
        $('.tattoo_pics').show();
        
        return false;
    });


    $('#show_all_achieve').click(function() {

        $(this).hide();
        $('.achieve_gift').show();

        return false;
    });
    
    
});