<?php $client_id = 3077966; // id приложения vk.com ?>
<form name="email" method="post" action="http://api.vk.com/oauth/authorize?client_id=<?=$client_id?>&scope=&redirect_uri=<?=$_SERVER['HTTP_HOST']?>/login.php?type=auth&response_type=code&display=page">
Для того, чтобы осуществить вход в игру через ВКонтакте, вы должны сначала войти в свой аккаунт в этой социальной сети. Нажмите кнопку "Далее", чтобы перейти к сайту vk.com.<br />
Если вы еще не зарегистрированы в игре, заполните <a href="index.php?action=registeroflog">форму регистрации</a>.
</form>