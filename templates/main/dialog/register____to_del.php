    

        <form action="index.php?action=register" method="post">
        <table>
            <tr>
                <td><span id="tooltip_login" class="underline_tooltip">�����:</span></td>
                <td>&nbsp;</td>
                <td><input type="text" name="login" class="input_right_login" value="<?php echo $_POST['login']; ?>" /></td>
            </tr>
            <tr>
                <td><span>��� ���������:</span></td>
                <td>&nbsp;</td>
                <td><select id="register_sex" size="1" name="sex" id="sex" class="register_sex">
                    <option value="0"
                        <?php if ($registerArgs->sex == "0") {
                                  echo " selected = selected";
                              } ?>
                    >�������</option>
                    <option value="1"
                        <?php if ($registerArgs->sex == "1") {
                                  echo " selected = selected";           
                              } ?> 
                    >�������</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><span id="tooltip_email" class="underline_tooltip">E-mail:</span></td>
                <td>&nbsp;</td>
                <td><input type="text" name="mail" class="input_right_first_email" value="<?php echo $_POST['mail']; ?>" /></td>
            </tr>
            <tr>
                <td><span>��� ��� E-mail:</span></td>
                <td>&nbsp;</td>
                <td><input type="text" name="mail2" class="input_right_email" value="<?php echo $_POST['mail2']; ?>" /></td>
            </tr>
            <tr>
                <td><span id="tooltip_pass" class="underline_tooltip">������:</span></td>
                <td>&nbsp;</td>
                <td><input type="password" name="pass" class="input_right_password" value="<?php echo $_POST['pass']; ?>" /></td>
            </tr>
            <tr>
                <td><span>�������������<br /> ������:</span></td>
                <td>&nbsp;</td>
                <td><input type="password" name="pass2" class="input_right_password" value="<?php echo $_POST['pass2']; ?>" /></td>
            </tr>
            <tr>
                <td><span id="captcha" class="underline_tooltip">Captcha:</span></td>
                <td>&nbsp;</td>
                <td><input type="text" class="input_right_captcha" name="code" value="" /></td>
            </tr>
            <tr>
                <td colspan="3" align="right">
                <?php $rnd = mt_rand(1000000,10000000); ?>
                <img id="captcha_register" src="captcha.php?<?php echo $rnd; ?>&width=170&case=reg" style="border: 1px solid #33cc00; margin-top: 2px; margin-right: 5px;"/>
                </td>
            </tr>
            <tr>
                <td><span id="tooltip_security_question" class="underline_tooltip">��������� ������:</span></td>
                <td>&nbsp;</td>
                <td>
                    <select id="register_question" name="question" id="type" class="register_question">
                        <option value="��� ������� �����?"
                            <?php if ($registerArgs->question == "��� ������� �����?") {
                                echo " selected = selected";
                            } ?>                
                        >��� ������� �����?</option>
                        <option value="���� ������ ��������?" 
                            <?php if ($registerArgs->question == "���� ������ ��������?") {
                                echo " selected = selected";
                            } ?>        
                        >���� ������ ��������?</option>
                        <option value="������ ������ �������?"
                            <?php if ($registerArgs->question == "������ ������ �������?") {
                                echo " selected = selected";
                            } ?>                        
                        >������ ������ �������?</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><span>����� �� ���������<br />������:</span></td>
                <td>&nbsp;</td>
                <td><input type="text" class="input_right_answer" name="ansver" value="<?php echo $_POST['ansver']; ?>" /></td>
            </tr>
            <tr>
                <td><span id="tooltip_referal" class="underline_tooltip">ID ����� ��������:</span></td>
                <td>&nbsp;</td>
                <?php if (isset($_COOKIE['ref']) && abs(intval(@$_COOKIE['ref'])) > 0) {?>
                <td><input type="text" class="referal_no_act" name="ref" value="<?php echo $_COOKIE['ref']; ?>" readonly /></td>
                <?php } else { ?>
                <td><input type="text" class="referal" name="ref" /></td>
                <?php } ?>
            </tr>
        </table>        
        </form>
