/**
 * Created by miloslawsky on 22.10.16.
 */

(function($) {
    var __popup = function () {
        this.template = {
            defaultButtons: '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>'
        };

        this.init = function () {
            self.initEvents();
//            self.template.defaultButtons = $('#esd-modal .modal-footer').html();
        };
        this.initEvents = function () {
            $(document).ajaxStop(self.handlers.ajaxStop);
            self.reInstallHandlers();
        };
        this.reInstallHandlers = function () {
            $('form.esdPopup').on('submit.popup', self.handlers.submit).removeClass('esdPopup').addClass('esdPopupReady');
            $('.esdPopup').on('click.popup', self.handlers.click).removeClass('esdPopup').addClass('esdPopupReady');
            $('button[type=submit],input[type=submit]', '#esd-modal .modal-footer').off('click.popup').on('click.popup', self.handlers.fakeSubmit);
        };
        this.handlers = {
            click: function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var title = $(this).attr('title');
                self.processRequest(href, 'get', null, title);
            },
            submit: function (e) {
                e.preventDefault();
                var form = $(this).blur();
//                var data = $(this).serializeArray();
                var url = form.attr('action');
                var title = form.attr('title');
                var method = form.attr('method') ? form.attr('method') : 'get';

                var data = new FormData();

                $('input, select, textarea',this).each(function(){
                  data.append($(this).attr('name'), $(this).val());
                });

                $('input[type=file]',this).each(function(){
                  data.append($(this).attr('name'), $(this).prop('files')[0]);
                });
                self.processRequest(url, method, data, title);
            },
            fakeSubmit: function(e){
                e.preventDefault();
                $('#esd-modal form').submit();
            },
            ajaxStop: function (e) {
                self.reInstallHandlers();
            }
        };
        this.processRequest = function (url, method, postData, title) {
            self.show();
            method = method ? method : 'get';
            $.ajax({
                url: url,
                data: postData,
                type: method,
                processData: false,
                contentType: false,
                success: function (data) {
                    content = (data && data.content)? data.content : data;
                    title   = (data && data.title)  ? data.title   : title;
                    buttons = (data && data.buttons)? data.buttons : self.template.defaultButtons;

                    self.setTitle(title);
                    self.setContent(content);
                    self.setButons(buttons);
                    self.reInstallHandlers();
                    $(document).trigger('popupAjaxComplete');
                },
                error: function (responce) {
                    self.setTitle('Error occured!');
                    self.setContent('<div class="alert alert-danger" role="alert">'+responce.responseText+'</div>');
                    self.setButons(self.template.defaultButtons);
                    $(document).trigger('popupAjaxComplete');
                },
                dataType: 'json'
            });
        };
        this.show = function(){
            $('#esd-modal .esd-loader-wrapper').show();
            if (!$('#esd-modal').data('bs.modal').isShown) {
                $('#esd-modal').modal('show');
            }
        };
        this.setContent = function(content) {
            $('#esd-modal .esd-loader-wrapper').hide();
            $('#esd-modalContent .content').html(content);
        };
        this.setTitle = function(title){
            title = title ? title : '';
            $('#esd-modalHeader h4').remove();
            $('#esd-modalHeader').append($('<h4>').html(title));
        };
        this.setButons = function(buttons){
            $('#esd-modal .modal-footer').html(buttons);
        };

        var self = this;
        self.init();
    };

    esd.addPlugin('popup', new __popup());
})(jQuery);
