    <!-- Диалоговое окно: "Восстановление пароля" [Ответ на секретный вопрос] -->
        <form name="email" method="post" action="/index.php?recover=answer">
            <span>Для восстановления пароля<br />
            заполните следующие поля<br />
            E-mail: </span><input type="text" class="recovery_mail" name="email" value="<?php echo $_POST['email']; ?>"/><br />
            <div id="repsonse_answer_box">
                <span>Секретный вопрос: </span><br />
                <?php 
                $view = _mysql_fetch_array(_mysql_exec("select * from players where email='" . $_POST['email'] . "' limit 1"));
                echo $view['question']; ?><br />
                <input type="text" class="response_answer" name="questionanswer" value="" /><br />
            </div>
        </form>

