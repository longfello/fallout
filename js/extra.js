$(document).ready(function() {
    // Всплывающие окна в инфоблоке
    $('#stats img').each(function() {
        $(this).tooltip({
            bodyHandler: function(){
                return $(this).next().html();
            },
            showURL: false,
            delay: 0,
            track: true
        });
    })

    // ajax-popup
  $('a.popup').each(function(){
    var href = $(this).data('href') || $(this).attr('href');
    $(this)
      .attr('href', href)
      .removeAttr('data-href')
      .data('buttons', $(this).data('buttons'))
      .removeAttr('data-buttons')
      .on('click', function(e){
        e.preventDefault();
        var buttons = $(this).data('buttons');
        if (!buttons) {
          buttons = {
            'Закрыть':'popupClose'
          };
        }
        var data={
          title: $(this).attr('title') || $(this).data('title') || $(this).text(),
          text: '...',
          buttons: buttons,
          url: $(this).attr('href')
        };
        $('body').trigger('popup', data);
      });
  });
});

