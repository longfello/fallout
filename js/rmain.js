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

});
