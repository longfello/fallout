$(document).ready(function() {
    var clipboard = new Clipboard('#copy_link');

    clipboard.on('success', function (e) {
        $("#copy_link .copy_finished").show();
        $("#copy_link .copy_blank").hide();
        setTimeout(function () {
            $("#copy_link .copy_finished").hide();
            $("#copy_link .copy_blank").show();
        }, 1500);

        e.clearSelection();
    });

    var clipboard_inpur = new Clipboard('#user_share_link');

    clipboard_inpur.on('success', function (e) {
        $("#copy_link .copy_finished").show();
        $("#copy_link .copy_blank").hide();
        setTimeout(function () {
            $("#copy_link .copy_finished").hide();
            $("#copy_link .copy_blank").show();
        }, 1500);

        e.clearSelection();
    });

    var clipboard_btn = new Clipboard('.copy_ref_btn');

    clipboard_btn.on('success', function(e) {

        $(e.trigger).find(".copy_finished").show();
        $(e.trigger).find(".copy_blank").hide();
        setTimeout(function () {
            $(e.trigger).find(".copy_finished").hide();
            $(e.trigger).find(".copy_blank").show();
        }, 1500);
        e.clearSelection();
    });

    var clipboard_block = new Clipboard('.copy_block');

    clipboard_block.on('success', function(e) {

        var id = $(e.trigger).attr('id');
        var link = $('.copy-link[data-clipboard-target=#'+id+']');

        console.log(link);
        $(link).find(".copy_finished").show();
        $(link).find(".copy_blank").hide();
        setTimeout(function () {
            $(link).find(".copy_finished").hide();
            $(link).find(".copy_blank").show();
        }, 1500);
        e.clearSelection();
    });
});