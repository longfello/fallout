<?php
/*
 * @var $this DefaultController
 * @var $form CActiveForm
 */
?>



<?

?>
<div class="rchat" id="rchat">
    <hr />
    <div id="volnorez_live_player" style="width:450px;height:27px;display:block;color:#33cc00"><br /><?= t::get('Слушать') ?> <a href="https://volnorez.com/radio-revival-online" title="<?= t::encHtml('радио Radio Revival Online') ?>"><?= t::get('радио Radio Revival Online') ?></a><br /><script type="text/javascript">var jscode_player_id="00133e35";var jscode_options="1f";var cShareBtnMode="local";var cSkinURL_player="https://volnorez.com/application/maxsite/plugins/wcdesign/skins/liveplayer_skin_modern.swf";var cSkinParams_player="clr_skin:0,clr_text:33cc00,clr_btn:33cc00,clr_btn_h:ccff00,clr_btn_d:ccff00,clr_volume:ccff00";</script><br /><script type="text/javascript" src="https://volnorez.com/application/maxsite/plugins/jscode/js/jscode_player.js"></script><br /></div>
    <hr />
    <?php if (Yii::app()->stat->clan): ?>
        <a id="obj_my_chatclans" href="/chatclan"><?= t::get('Клановый чат') ?></a>   |
    <? endif; ?>
    <a href="radio.php?back=chat"><?= t::get('Радио') ?></a>   |
    <a href="<?= t::get_forum_link(); ?>viewtopic.php?f=2&t=2" target='_blank'><?= t::get('Правила чата') ?></a>
    <hr />
    <div id="ignoreInfo">
        <?= t::get('У вас в игноре <span id="ignoreCount"></span> человек(а):') ?> <span id="ignorePlayers"></span>
    </div>
    <div id="ignoreList"></div>
    <hr />
    <div class="sendingBlock">
        <? $form = $this->beginWidget('CActiveForm', array(
            'id' => 'chatForm',
            'htmlOptions' => array('autocomplete' => 'off')
        )); ?>
        <?= CHtml::image('/images/smilies/ag.gif', t::get('Показать смайлики'), array('title' => t::get('Показать смайлики'), 'id' => 'showSmileBtn')) ?>
        <?= $form->textField($model, 'message', array('class' => 'messageField'))  ?>
        <?= CHtml::submitButton(t::get('Отправить')); ?><br />
        <div id="privateBlock">
            <?= $form->checkBox($model, 'to_player', array('class' => 'private pointer')) ?>
            <?= $form->labelEx($model, 'to_player', array('class' => 'pointer')) ?>
            <?= CHtml::checkBox('ignore', false, array('class' => 'pointer ignore')) ?>
            <?= CHtml::label(t::get('Игнор'), 'ignore', array('class' => 'pointer')) ?>
        </div>
        <? $this->endWidget() ?>
    </div>
    <div class="smileContainer">
        <div id="hiddenSmiles" class="smiles">
            <img name="1" img src="/images/smilies/ab.gif" border="0" alt=":)">
            <img name="2" img src="/images/smilies/ac.gif" border="0" alt=":(">
            <img name="3" img src="/images/smilies/ad.gif" border="0" alt=";)">
            <!--<img name="4" img src="/images/smilies/ae.gif" border="0" alt=":-P">-->
            <img name="5" img src="/images/smilies/aa.gif" border="0" alt="O:-)">
            <img name="6" img src="/images/smilies/af.gif" border="0" alt="8-)">
            <img name="7" img src="/images/smilies/ag.gif" border="0" alt=":-D">
            <img name="8" img src="/images/smilies/ah.gif" border="0" alt=":-[">
            <img name="9" img src="/images/smilies/ai.gif" border="0" alt="o_O">

            <img name="10" img src="/images/smilies/aj.gif" border="0" alt=":-*">
            <img name="11" img src="/images/smilies/ak.gif" border="0" alt=":((">
            <img name="12" img src="/images/smilies/al.gif" border="0" alt=":-X">
            <img name="13" img src="/images/smilies/am.gif" border="0" alt="-:o">
            <img name="14" img src="/images/smilies/an.gif" border="0" alt=":-|">
            <img name="15" img src="/images/smilies/ao.gif" border="0" alt=":-/">
            <img name="16" img src="/images/smilies/ap.gif" border="0" alt="*JOKINGLY*">
            <img name="17" img src="/images/smilies/aq.gif" border="0" alt="*DIABLO*">
            <img name="18" img src="/images/smilies/ar.gif" border="0" alt="*MUZ*">
            <img name="19" img src="/images/smilies/as.gif" border="0" alt="*KISSED*">

            <img name="20" img src="/images/smilies/at.gif" border="0" alt=":-!">
            <img name="21" img src="/images/smilies/au.gif" border="0" alt="*TIRED*">
            <img name="22" img src="/images/smilies/av.gif" border="0" alt="*STOP*">
            <img name="23" img src="/images/smilies/aw.gif" border="0" alt="*KISSING*">
            <img name="24" img src="/images/smilies/ax.gif" border="0" alt="*ROSE*">
            <img name="25" img src="/images/smilies/ay.gif" border="0" alt="*THUMBS UP*">
            <img name="26" img src="/images/smilies/az.gif" border="0" alt="*DRINK*">
            <img name="27" img src="/images/smilies/ba.gif" border="0" alt="*IN LOVE*">
            <!--<img name="28" img src="/images/smilies/bb.gif" border="0" alt="@=">-->
            <img name="29" img src="/images/smilies/bc.gif" border="0" alt="*HELP*">

            <img name="30" img src="/images/smilies/bd.gif" border="0" alt="\m/">
            <img name="31" img src="/images/smilies/be.gif" border="0" alt="%-)">
            <img name="32" img src="/images/smilies/bf.gif" border="0" alt="*OK*">
            <img name="33" img src="/images/smilies/bg.gif" border="0" alt="*WASSUP*">
            <img name="34" img src="/images/smilies/bh.gif" border="0" alt="*SORRY*">
            <img name="35" img src="/images/smilies/bi.gif" border="0" alt="*BRAVO*">
            <img name="36" img src="/images/smilies/bj.gif" border="0" alt="*ROFL*">
            <img name="37" img src="/images/smilies/bk.gif" border="0" alt="*PARDON*">
            <img name="38" img src="/images/smilies/bl.gif" border="0" alt="*NO*">
            <img name="39" img src="/images/smilies/bm.gif" border="0" alt="*CRAZY*">

            <img name="41" img src="/images/smilies/bo.gif" border="0" alt="*DANCE*">
            <img name="42" img src="/images/smilies/bp.gif" border="0" alt="*YAHOO*">
            <img name="43" img src="/images/smilies/bq.gif" border="0" alt="*HI*">
            <img name="44" img src="/images/smilies/br.gif" border="0" alt="*BYE*">
            <img name="45" img src="/images/smilies/bs.gif" border="0" alt="*YES*">
            <img name="46" img src="/images/smilies/bt.gif" border="0" alt="*ACUTE*">
            <img name="47" img src="/images/smilies/bu.gif" border="0" alt="*WALL*">
            <img name="48" img src="/images/smilies/bv.gif" border="0" alt="*WRITE*">
            <img name="49" img src="/images/smilies/bw.gif" border="0" alt="*SCRATCH*">

            <img name="50" img src="/images/smilies/friends.gif" border="0" alt="*FRIENDS*">
            <img name="51" img src="/images/smilies/punish.gif" border="0" alt="*PUNISH*">
            <img name="52" img src="/images/smilies/take_example.gif" border="0" alt="*TAKE_EXAMPLE*">
            <img name="53" img src="/images/smilies/black_eye.gif" border="0" alt="*BLACK_EYE*">
            <img name="54" img src="/images/smilies/ireful.gif" border="0" alt="*IREFUL*">
            <img name="55" img src="/images/smilies/boast.gif" border="0" alt="*BOAST*">
            <!-- Новые смайлики с постапокалиптической тематикой -->
            <img name="55" img src="/images/smilies/gauss-win.gif" border="0" alt="*GAUSSWIN*">
            <img name="55" img src="/images/smilies/coolpilot.gif" border="0" alt="*COOLPILOT*">
            <img name="55" img src="/images/smilies/combatarmor.gif" border="0" alt="*COMBATARMOR*">
            <img name="56" img src="/images/smilies/bomb.gif" border="0" alt="*BOMB*">
            <img name="57" img src="/images/smilies/gauss.gif" border="0" alt="*GAUSS*">
            <img name="58" img src="/images/smilies/sulik.gif" border="0" alt="*SULIK*">
            <img name="59" img src="/images/smilies/sad.gif" border="0" alt="*SAD*">
            <img name="60" img src="/images/smilies/nuke-danger.gif" border="0" alt="*DANGER*">
            <img name="61" img src="/images/smilies/fighter.gif" border="0" alt="*FIGHTER*">
            <img name="62" img src="/images/smileys/bb2.gif" border="0" alt="*HERCULES*">
            <img name="63" img src="/images/smilies/flag_of_truce.gif" border="0" alt="*PEACE*">
            <img name="64" img src="/images/smilies/hi_hat.gif" border="0" alt="*HAT*">
            <img name="65" img src="/images/smilies/hiteoid_2.gif" border="0" alt="*RAKE*">
            <img name="66" img src="/images/smilies/js_flirt.gif" border="0" alt="*CUTE*">
            <img name="67" img src="/images/smilies/js_handshake.gif" border="0" alt="*HANDSHAKE*">
            <img name="68" img src="/images/smilies/kidrock_01.gif" border="0" alt="*BAN*">
            <img name="69" img src="/images/smilies/laie_48.gif" border="0" alt="*BAYAN*">
            <img name="70" img src="/images/smilies/pop.gif" border="0" alt="*POP*">
            <img name="71" img src="/images/smilies/popcorn2.gif" border="0" alt="*POPCORN*">
            <img name="72" img src="/images/smilies/popka.gif" border="0" alt="*POPKA*">
            <img name="73" img src="/images/smilies/prayer.gif" border="0" alt="*PRAYER*">
            <img name="74" img src="/images/smilies/rtfm.gif" border="0" alt="*AGREEMENT*">
            <img name="75" img src="/images/smilies/russian_ru.gif" border="0" alt="*RUSSIAN*">
            <img name="76" img src="/images/smilies/sarcastic.gif" border="0" alt="*SARCASTIC*">
            <img name="77" img src="/images/smilies/smile12.gif" border="0" alt="*CRY*">
            <img name="78" img src="/images/smilies/spiteful.gif" border="0" alt="*SPITEFUL*">
            <img name="79" img src="/images/smilies/facepalm.gif" border="0" alt="*FACEPALM*">
            <img name="80" img src="/images/smilies/boy_smile.gif" border="0" alt="*BSMILE*">
            <img name="81" img src="/images/smilies/boy_sad.gif" border="0" alt="*BSAD*">
            <img name="82" img src="/images/smilies/boy_laughter.gif" border="0" alt="*BLAUGHTER*">
            <img name="83" img src="/images/smilies/boy_surprise.gif" border="0" alt="*BSURPRISE*">
            <img name="84" img src="/images/smilies/boy_gibe.gif" border="0" alt="*BGIBE*">
            <img name="85" img src="/images/smilies/boy_wink.gif" border="0" alt="*BWINK*">
            <img name="86" src="/images/smilies/boy_anger.gif" border="0" alt="*BANGER*">
            <img name="87" src="/images/smilies/boy_apathy.gif" border="0" alt="*BAPATHY*">
            <img name="88" src="/images/smilies/boy_uneasiness.gif" border="0" alt="*BUNEASINESS*">
            <img name="89" src="/images/smilies/boy_slope.gif" border="0" alt="*BSLOPE*">
        </div>
        <div id="mainSmiles" class="smiles">
<!--             <img name="17" src="images/smileys/santa-1.gif" border="0" alt="*SANTA*">
             <img name="18" src="images/smileys/santa-2.gif" border="0" alt="*SANTA_HI*">
             <img name="19" src="images/smileys/santa-3.gif" border="0" alt="*SANTA_HOHO*"> -->
            <img name="1" img src="/images/smileys/1.gif" border="0" alt="!:)">
            <img name="2" img src="/images/smileys/2.gif" border="0" alt="!:(">
            <img name="3" img src="/images/smileys/3.gif" border="0" alt="!:-D">
            <img name="4" img src="/images/smileys/4.gif" border="0" alt="!:-o">
            <img name="5" img src="/images/smileys/5.gif" border="0" alt="!:-P">
            <img name="6" img src="/images/smileys/6.gif" border="0" alt="!;-)">
            <img name="7" img src="/images/smileys/angry.gif" border="0" alt="!*ANGRY*">
            <img name="8" img src="/images/smileys/nedovolstvo.gif" border="0" alt="!*DISCONTENT*"">
            <img name="9" img src="/images/smileys/krasniy.gif" border="0" alt="!:-[">
            <img name="10" img src="/images/smileys/o4ki.gif" border="0" alt="!8-)">
            <!-- Новые смайлики с постапокалиптической тематикой -->
            <img name="11" img src="/images/smileys/daisy-angel.gif" border="0" alt="*Daisy*">
            <img name="12" img src="/images/smileys/flower.gif" border="0" alt="*FLOWER*">
            <img name="13" img src="/images/smileys/nuka.gif" border="0" alt="*NUKA*">
            <img name="14" img src="/images/smileys/devil.gif" border="0" alt="*DEVIL*">
            <img name="15" img src="/images/smileys/laugh.gif" border="0" alt="*LOL*">
            <img name="16" img src="/images/smileys/bye2.gif" border="0" alt="*BYE2*">
        </div>
    </div>
    <div id="ban" class="banBlock"></div>
    <div class="chat-row">
        <div class="chatUsers-wrapper">
            <div id="chatUsers"></div>
            <hr>
            <div><?= t::get('Всего в чате:') ?> <span id="chatTotal"></span></div>
        </div>
        <div id="chatLineHolder"></div>
    </div>
</div>
