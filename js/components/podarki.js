$(document).ready(function() {
    
    $('#gift_button').click(function() {
        
        var content = null;
        var url = $('#formmsg').attr('id');
        var selected_gift = $('#formmsg tr:has(input:checked)');
        var gift_name = $('.gift_name', selected_gift).text();
        var gift_owner = $('#gift_owner').text();
        
        if (!selected_gift.length) {
            content = 'Подарок не выбран';
            alertUI(content);
        } 
        else {
            content = 'Вы действительно хотите подарить подарок "' + gift_name + '" игроку ' + gift_owner + '?';
            dialog(content, url);
        }
    
        return false;
     
    });
    
});