$(function() {
    window.getGroupItems = function(itemsId, type, place, to) {
        $('.group').slideUp();
        var currentBlock = $('#items_' + itemsId);
        var groupBlock = currentBlock.children('.group');
        
        currenFile = location.pathname;
              
        var parametrOfAction = '';
        switch (place) {
            case 'itemsInventory':
                parametrOfAction = '?equip=';
                break;
            case 'itemsInStorage':
                parametrOfAction = '?to=1337&n=1&put=';
                break;
            case 'itemsForStorage':
                parametrOfAction = '?n=1&donate=';
                break;
            case 'itemsInClanStorage':
                parametrOfAction = '?n=1&to=' + to + '&put=';
                break;
            case 'itemsInImarket':
                parametrOfAction = '?item=';
                break
        }
              
        var parametrOfSorterForUrl = type == '0' ? '' : '&type=' + type;
        var parametrOfSellForUrl = place == 'recipesInventory' ? 'action=sellr&n=1&item' : 'sell';
        var parametrOfThrowForUrl = place == 'recipesInventory' ? 'action=off&n=1&item' : 'trash';
        
        if (groupBlock.is(':visible')) {
            groupBlock.slideUp();
        } else {
            if (groupBlock.is(':hidden')) {
                groupBlock.slideDown();
            } else {
                $.getJSON('/ajax_items_data_gradation.php', ({id : itemsId,  place : place}), function(data) {
        
                    // Array keys from json
                    var countElement = '';
                    var keysArrayFromJson = new Array();
                    $.each(data, function(k, v) {
                        keysArrayFromJson.push(k);
                        countElement++;
                    });
                    
                    if (countElement != 1) {
                        var block = '<div class="indention"></div>'
                                  + '<div class="mapping"></div>';      
                    } else {
                        var block = '<div class="indention"></div>'
                                  + '<div class="mapping_last_item"></div>';  
                    }
                     
                    $.each(data, function(key, val) {
                        block += '<div class="' + val.color + '">'
                               + '<img class="item_expanded_list" src="' + $('#images_item_' + itemsId).attr('src') + '" />'
                               + '<div class="throw_out_item"></div>'
                               + '<div class="items_count">' + val.count + '</div>'
                               + '<div class="item_inner_text">';
                        
                       if (val.action) {
                            block += '<a href="' + currenFile + parametrOfAction + val.mostBrokenItem + parametrOfSorterForUrl + '">' + val.action + '</a><br />'; 
                       }
                       
                       if (val.actionBySell) {
                            block += '<a href="' + currenFile + '?' + parametrOfSellForUrl + '=' + val.mostBrokenItem + parametrOfSorterForUrl + '">' + val.actionBySell + '</a><br />'
                       }
                      if ((place != 'itemsInStorage') 
                          && (place != 'itemsForStorage') 
                          && (place != 'itemsInClanStorage') 
                          && (place != 'itemsInImarket')
                      ) {
                          block +=  '<a href="' + currenFile + '?' + parametrOfThrowForUrl + '=' + val.mostBrokenItem + parametrOfSorterForUrl + '">Выбросить</a>';
                      }
                      block += '</div>';
                        if (key != 5) {
                            if (countElement > 1) {
                                 if (key != keysArrayFromJson[countElement - 2]) {
                                    if (countElement != 2) {
                                        block += '</div><div class="indention"></div>'
                                           + '<div class="mapping"></div>';
                                    }
                                } else {
                                    block += '</div><div class="indention"></div>'
                                       + '<div class="mapping_last_item"></div>';              
                                }    
                            }
                        } else {
                            block += '<div class="defected"></div></div>';
                        }
                    });
                    currentBlock.append('<div class="group">' + block + '</div>');  
                });
            }
        }
    }
}); // end ready