$(document).ready(function() {

/* Bootstrap modal with plugin modal manager */
// Modal progress bar
$.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
    '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
        '<div class="progress-bar" style="width: 100%;"></div>' +
        '</div>' +
        '</div>';

$.fn.modalmanager.defaults.resize = true;

$('[data-source]').each(function(){
    var $this = $(this),
        $source = $($this.data('source'));

    var text = [];
    $source.each(function(){
        var $s = $(this);
        if ($s.attr('type') == 'text/javascript'){
            text.push($s.html().replace(/(\n)*/, ''));
        } else {
            text.push($s.clone().wrap('<div>').parent().html());
        }
    });

    $this.text(text.join('\n\n').replace(/\t/g, '    '));
});


// Modal window
var $modal = $('#ajax-modal');

$('.access').on('click', function(){
    // create the backdrop and wait for next modal to be triggered
    $('body').modalmanager('loading');

    $modal.load($(this).attr('href'), '', function(){
        $modal.modal();
    });

});

$modal.on('click', '.update', function(){
    $modal.modal('loading');
    setTimeout(function(){
        $modal
            .modal('loading')
            .find('.modal-body')
            .prepend('<div class="alert alert-info fade in">' +
                'Updated!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                '</div>');
    }, 1000);
});


/* Sending data from form */
$(document).on('submit', '#saveBonus', function() {
    var form = $(this);
    form.ajaxSubmit({
        'dataType': 'json',
        beforeSubmit: function() {
            $modal.modal('loading')
        },
        success: function(data) {
            $modal.modal('loading');

            $modal.find('.successMsg').remove();
            $modal.find('.errorMsg').remove();


            if (!$.trim(data.error)) {
                if ($.trim(data.save)) {
                    $('#pointId-' + form.data('id')).addClass('marked');
                }
                else if ($.trim(data.del)) {
                    $('#pointId-' + form.data('id')).removeClass('marked');
                }

                if (!$modal.find('.successMsg').length) {
                    $modal.find('.modal-body')
                        .prepend('<div class="alert alert-success fade in successMsg"><strong>' +
                            'Обновлено!</strong><button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                }
            }
            else {
                if (!$modal.find('.errorMsg').length) {
                    $modal.find('.modal-body')
                        .prepend('<div class="alert alert-danger fade in errorMsg"><strong>' +
                            'Ошибка!</strong><br />' + data.error +  '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                }
            }
        }
    });

    return false;
});


}); // End ready