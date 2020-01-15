        
        <form action="login.php" method="post">
        <ul>
            <li><span>Логин</span></li>
            <li><input type="text" name="user" /></li>
            <li><span>Пароль</span></li>
            <li><input type="password" name="pass" /></li>
            <input type="hidden" name="ret" value="<?php echo $ret; ?>" />
            
<?php
require_once 'vk_auth.php';
$client_id = 3077966; // id приложения vk.com

print_r(get_data_vk());

$ip = _mysql_real_escape_string($_SERVER["REMOTE_ADDR"]);
$dt = time();
_mysql_query("DELETE FROM ips WHERE ip = '$ip' AND $dt - dt > 3600;");
$R = _mysql_fetch_assoc(_mysql_query("SELECT * FROM ips WHERE ip = '$ip';"));

$rnd = mt_rand(1000000,10000000);

/*
if ($R["id"]) echo <<<EOT
            <li><span>Captcha:</span></li>
            <li><input type="text" name="code" value="" size="18" style="border: 1px solid #33cc00;"/></li>
            <li><img src="captcha.php?{$rnd}&case=login" style="border: 1px solid #33cc00;margin-top: 10px;"/></li>
EOT;
*/
if (isset($_GET['reg'])) { ?>
  <script>
    $(document).ready(ShowRegBox);
    function ShowRegBox(){
      $('#registration').slideDown(1000);
    }
  </script>
<?php } ?>
            <li id="recover_link"><a href="#">Забыли пароль?</a></span></li>
            <li id="registration_link"><a href="#">Регистрация</a></li>
            <li><span style="position: relative; bottom: 4px;">Войти через</span> <a id="vk_entries" href="http://api.vk.com/oauth/authorize?client_id=<?=$client_id?>&scope=&redirect_uri=<?=$_SERVER['HTTP_HOST']?>/login.php?type=auth&response_type=code&display=page"><img style="position: relative; top: 4px;" src="/images/button 2.png" /></a></li>
        </ul>
        </form>
        
