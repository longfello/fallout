$(document).ready(function() {
    
    // Вывод данных для ежедневного отчёта
    $('.daily_donate_row').click(function() {
        var current_block = $(this);
        var date = $('.donate_date', current_block);
        
        var date_text = $.trim(date.text());
        
        if ($('ul', current_block).length == 0) {
            
            $.ajax({
                url: 'ajax_donate_statistics.php',
                dataType: 'json',
                data: { date : date_text, type_row : 'daily' },
                success: function(data) {
                    var list = []
                    $.each(data, function(key, val){
                        list.push('<li>' + val.user_name + ' ' + val.tt_user_id + ' (REF ' + val.source + '): '+ val.total + ' (' + val.pay_name + ')' + '</li>');
                    });
                    
                    current_block.css({ 'border' : '1px dashed green', 'backgroundColor' : '#0d0d0d'});
                    date.css({ 'color' : 'red' });
                    current_block.append('<ul>' + list.join('') + '</ul>');
                }
            });
            
        }
    });
    
    // Вывод данных для ежемесячного отчёта
    $('.montly_donate_row').click(function() {
        var current_block = $(this);
        var date = $('.donate_date', current_block);
        
        var date_text = $.trim(date.text());
        
        if ($('ul', current_block).length == 0) {
            
            $.ajax({
                url: 'ajax_donate_statistics.php',
                dataType: 'json',
                data: { date : date_text, type_row : 'montly' },
                success: function(data) {
                    var list = []
                    $.each(data, function(key, val){
                        list.push('<li>REF ' + val.source + ': '+ val.total + ' (' + val.pay_name + ')' + '</li>');
                    });
                    
                    current_block.css({ 'border' : '1px dashed green', 'backgroundColor' : '#0d0d0d' });
                    date.css({ 'color' : 'red' });
                    current_block.append('<ul>' + list.join('') + '</ul>');
                }
            });
            
        }
    });
    
});