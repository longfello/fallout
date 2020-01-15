VK.init({apiId: '3077966', onlyWidgets: true});

$(document).ready(function() {
    function authInfo(response) {
        var user_id = false;
            if (response.session) {
                user_id = response.session.mid;
        }
        if (!user_id) {
            $('#vk_entries').attr('href', 'login.php?type=auth&error_vk_no_auth');
        }
    }
    VK.Auth.getLoginStatus(authInfo);
});