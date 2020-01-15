<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title><? echo $title . ' - ' . $site_com; ?></title>
<meta name="description" content="<?= t::encHtml('header-tpl-meta-description') ?>" />
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<link rel="stylesheet" type="text/css" href="/css/jquery-ui-1.8.14.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/cusel.css" />
<script type="text/javascript" src="/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="/js/login.js"></script>
<script type="text/javascript" src="/js/jquery.tooltip.min.js"></script>
<script type="text/javascript" src="/js/cusel-min-2.4.js"></script>
<!-- Код Google Analitics -->
<script>
window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
ga('create', 'UA-71161923-1', 'auto');
ga('send', 'pageview');
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script>
<script type="text/javascript" src="//userapi.com/js/api/openapi.js?34"></script>
<script type="text/javascript">VK.init({apiId: '3077966', onlyWidgets: true});</script>
<script type="text/javascript">
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
</script>
<script type="text/javascript" src="/js/md5.js"></script>
</head>
<body>
      <div id="vk_auth" onclick="VK.Auth.login(authInfo);"></div>
<div id="container">