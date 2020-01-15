$(document).ready(function() {

    getRadio();


    // Получить последние 3 сообщения из радио
    function getRadio() {
        $.get('inner/default/radio', {}, function(r) {

            var radioLines = [];
            for (var i = 0; i < r.data.length; i++) {
                radioLines[i] = render(r.data[i]);
            }

            $('#radioBox').html(radioLines.join(''));

        }, 'json');

        setTimeout(getRadio, 5000);
    }


    // Отрисовать HTML
    function render(params) {

        var arr = [
            '<li>',
            '<span class="time">', params.time, '</span> ',
            //'<span class="nick ', '" data-user-id="', params.user_id ,'" title="', params.user_id, '">' , params.user, '</span> ',
            '&raquo;&raquo; <span class="text">', params.chat, '</span></li>'
        ];

        return arr.join('');
    }

});
