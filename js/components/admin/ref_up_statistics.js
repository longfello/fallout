$(document).ready(function() {
    
    // Вывод данных по рефам
    $('.ref_source_row').click(function() {
        var current_block = $(this);
        
        var ref_id = $.trim($('span', current_block).text());
        
        if ($('ul', current_block).length == 0) {
            
            $.ajax({
                url: 'ajax_ref_up_statistics.php',
                dataType: 'json',
                data: { ref_id : ref_id },
                success: function(data) {
                    var list = []
                    $.each(data, function(key, val){
                        list.push('<li>Lvl ' + val.level + ': ' + val.count_players + '</li>');
                    });
                    
                    current_block.css({ 'border' : '1px dashed green', 'backgroundColor' : '#0d0d0d'});
                    current_block.css({ 'color' : 'red' });
                    current_block.append('<ul>' + list.join('') + '</ul>');
                }
            });
            
        }
    });
    
});