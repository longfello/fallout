<?
/**
 * @var $this Controller
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= t::iso(); ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
    Yii::app()->clientScript->registerCssFile('/css/frame.css');
    Yii::app()->clientScript->registerCssFile('/css/content.css');
    Yii::app()->clientScript->registerScriptFile('/js/show_inf.js', CClientScript::POS_HEAD);
?>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1193790280698281');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1193790280698281&ev=PageView&noscript=1"/></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
<title><?= $this->pageTitle ?></title>
<base href="<?= Yii::app()->request->getBaseUrl(true) ?>" />
</head>
<body onLoad="on_load();" data-uid="<?=(int)Yii::app()->stat->id?>">
<div class="hint" id="hint1" style="z-index: 1005"></div><div class="hint" id="formmsg1" style="position:absolute; visibility:hidden;"></div>
<div id="wrapper">
    <div id="player_menu">
        <div id="player_button"><a href="/stats.php?status=info"><img src="/images/frame/<?=t::iso()?>/player_up_off.png" /></a></div>
        <div id="support_player">
            <table>
                <tr>
                    <td class="player_menu_header"></td>
                </tr>
                <tr>
                    <td class="player_menu_main">
                        <ul>
                            <li><a href="/stats.php?status=info"><?=t::get('Информация')?></a></li>

                            <?php //if ($PET): ?>
                                <li><a href="/stats.php?status=pet"><?=t::get('Питомец')?></a></li>
                            <?php //endif; ?>
                            <li><a href="/perk.php"><?=t::get('Способности')?></a></li>
                            <li><a href="/stats.php?status=stat"><?=t::get('Статистика')?></a></li>
                            <li><a href="/player/appearance"><?=t::get('Внешность')?></a></li>
                        </ul>
                    </td>
                </tr>
                <td class="player_menu_footer"></td>
            </table>
        </div>
    </div>
    <table id="right_horizontal_menu">
        <tr id="horizontal_menu" class="menu">
            <td><a href="/inventory.php"><img src="/images/frame/<?=t::iso()?>/inventory_normal_off.png" /></a></td>
            <td><a href="/queststate.php"><img src="/images/frame/<?=t::iso()?>/quests_normal_off.png" /></a></td>
            <td><a href="/crafting.php"><img src="/images/frame/<?=t::iso()?>/craft_normal_off.png" /></a></td>
            <td><a id="log_btn" href="/log.php"><img src="/images/frame/<?=t::iso()?>/logs_normal_off_roff.png" /></a></td>
            <td class="menu_platinum"><a href="/donate.php"><img src="/images/frame/<?=t::iso()?>/nuka_normal.png" /></a></td>
            <td><a href="/chat"><img src="/images/frame/<?=t::iso()?>/chat_off.png" /></a></td>
        </tr>
    </table>
    <div id="cable_horizontal_main"></div>
    <div id="left_side_bar">
        <tr>
        <table id="info_menu">
            <tr>
                <td class="info_menu_header"></td>
            </tr>
            <tr>
                <td class="info_menu_content">
                    <div id="info_block">
                        <div id="title_info">
                        <? if (Yii::app()->stat->clan) : ?>
                            <a href='clans.php?view=my' style="text-decoration: none">
                                <img src="/images/clans/<?= Yii::app()->stat->clan ?>.gif" align="absmiddle" alt="<?=t::get('Ваш клан')?>" width="17" height="15">
                            </a>
                        <? endif ?>
                        <?= Extra::getUserRankIcon(Yii::app()->stat->rank) ?>
                        <a id="obj_mail_link" href="/mail.php?view=inbox"><img  id="obj_mail_img" src="/images/unread.gif" align="absmiddle" alt="<?=t::get('Почта')?>" /></a>
                          <?php t::widget(); ?>
                        </div>
                        <ul id="stats">
                            <li>
                                <img src="/images/frame/icon_hp.png" class="img_stat" title="" />
                                <span class="tooltip_box">
                                <div class="progress_bar_title"><?=t::get('Здоровье'); ?></div>
                                        <div class="progress_bar_content"></div>
                                        <div class="progress_bar_block">
                                            <div class="progress_bar_bg">[</div>
                                            <div class="progress_bar progress_bar_bg">
                                                <img src="/images/health.png" height="6" width="<?= Extra::getBarStatPercent(Yii::app()->stat->hp, Yii::app()->stat->max_hp) ?>%" />
                                            </div>
                                            <div class="progress_bar_bg">]</div>
                                        </div>
                                </span>
                                <span id="id_hp"><?=Yii::app()->stat->hp ?>/<?=Yii::app()->stat->max_hp?></span>
                            </li>
                            <li>
                                <img src="/images/frame/icon_energy.png" class="img_stat" title="" />
                                <span class="tooltip_box">
                                <div class="progress_bar_title"><?=t::get('Энергия'); ?></div>
                                    <div class="progress_bar_content"></div>
                                    <div class="progress_bar_block">
                                        <div class="progress_bar_bg">[</div>
                                        <div class="progress_bar progress_bar_bg">
                                            <img src="/images/energy.png" height="6" width="<?= Extra::getBarStatPercent(Yii::app()->stat->energy, Yii::app()->stat->max_energy) ?>%" />
                                        </div>
                                        <div class="progress_bar_bg">]</div>
                                    </div>
                                </span>
                                <span id="id_energy"><?=Yii::app()->stat->energy ?>/<?=Yii::app()->stat->max_energy?></span>
                            </li>
                            <li>
                                <img src="/images/frame/icon_endurance.png" class="img_stat" />
                                <span class="tooltip_box"><?=t::get('Походные очки'); ?></span>
                                <span id="id_pohod"><?php echo Yii::app()->stat->pohod ?></span>
                            </li>
                            <li>
                                <img src="/images/frame/icon_weight.png" class="img_stat" title="" />
                            <span class="tooltip_box">
                            <div class="progress_bar_title"><?=t::get('Вес'); ?></div>
                                    <div class="progress_bar_content"></div>
                                    <div class="progress_bar_block">
                                        <div class="progress_bar_bg">[</div>
                                        <div class="progress_bar progress_bar_bg">
                                            <img src="/images/weight.png" height="6" width="<?= Extra::getBarStatPercent(Yii::app()->eq->weight, Tool::gameGetMaxCarryWeight()) ?>%" />                                 </div>
                                        <div class="progress_bar_bg">]</div>
                                    </div>
                            </span>
                                <span id="id_weight"><?php echo Yii::app()->eq->weight . '/' . Tool::gameGetMaxCarryWeight() ;?></span>
                            </li>
                        </ul>
                        <ul id="resources">
                            <li><img src="/images/gold.png"/><span id="id_gold"><?php echo Yii::app()->stat->gold ?></span></li>
                            <li><img src="/images/platinum.png" /><span id="id_platinum"><?php echo Yii::app()->stat->platinum ?></span></li>
                            <?php if ($event = NkrEvents::getCurrentEvent()) { ?>
                                <li title="<?= t::get('Доллары НКР') ?>">
                                    <?php if ($event->news) { echo("<a href='".$event->news->getURL()."'>"); } ?>
                                    <img src="/images/nkr.png" /><span id="id_nkr"><?php echo Yii::app()->stat->model->getNkrCount() ?></span>
                                    <?php if ($event->news) { echo("</a>"); } ?>
                                </li>
                            <?php } ?>
                            <li><img src="/images/tokens.png" /><span id="id_tokens"><?php echo Yii::app()->stat->tokens ?></span></li>
                            <li>
                              <?php $text = t::get('Опыт до уровня %1',  Yii::app()->stat->level + 1); ?>
                                <table id="exp_stat" onmouseover="hint('<?= $text ?>:<br> <center><?= Yii::app()->stat->exp ?>/<?= Extra::getUserExperienceBeforeNextLevel()?> [<?= Extra::getUserExperienceBeforeNextLevelInPercent() ?>%]</center>', event);" onmouseout=c();>
                                <tr>
                                    <td bgcolor="#000000"><?= $text ?>: <br><center><?= Yii::app()->stat->exp ?>/<?= Extra::getUserExperienceBeforeNextLevel()?></center></td>
                                </tr></table>
                            </li>
                        </ul>
                        <div id="lamp"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="info_menu_footer"></td>
            </tr>
        </table>
        </tr>
        <tr>
            <table id="vertical_menu" class="menu">
                <tr>
                    <td><div id="cable_info_vmenu"></div></td>
                </tr>
                <tr>
                    <td class="vertical_menu_header"></td>
                </tr>
                <tr>
                    <td class="vertical_menu_content">
                        <table id="stripe">
                            <tr>
                                <td class="stripe_header"></td>
                            </tr>
                            <tr>
                                <td class="stripe_main"></td>
                            </tr>
                            <tr>
                                <td class="stripe_footer"></td>
                            </tr>
                        </table><!-- #stripe -->
                        <table id="vertical_button">
                            <tr>
                                <td>
                                    <?php
                                    switch (Yii::app()->stat->travel_place)
                                    {
                                        case '/labyrinth.php':
                                            echo '<a href="/labyrinth.php"><img src="/images/frame/'.t::iso().'/map_normal.png" /></a>';
                                            break;
                                        case '/caves.php':
                                            echo '<a href="/caves.php"><img src="/images/frame/'.t::iso().'/map_normal.png" /></a>';
                                            break;
                                        case '/pustosh.php':
                                            echo '<a href="/pustosh.php"><img src="/images/frame/'.t::iso().'/wasteland_normal.png" /></a>';
                                            break;
                                        default:
                                            echo '<a href="/city.php"><img src="/images/frame/'.t::iso().'/map_normal.png" /></a>';
                                    }
                                    ?>
                                    <div id="wires"><img src="/images/frame/<?=t::iso()?>/wires.png" /></div>
                                </td>
                            </tr>
                            <tr>
                                <td><a href="/clans.php"><img src="/images/frame/<?=t::iso()?>/clan_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/chatclan"><img src="/images/frame/<?=t::iso()?>/clanchat_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/statistic.php"><img src="/images/frame/<?=t::iso()?>/players_normal.png" /></a></td>
                            </tr>
                            <?php
                            $last_news = _mysql_fetch_object(_mysql_exec("SELECT
                            `id`
                            FROM
                            `news`
                            WHERE `section`='news' AND `active`='1'
                            ORDER BY `id` DESC
                            LIMIT 1"));
                            ?>
                            <tr>
                                <td><a href="/new/<?= $last_news->id; ?>"><img src="/images/frame/<?=t::iso()?>/news_normal_<?php echo (!Yii::app()->stat->read_news) ? 'ron' : 'roff'; ?>.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="<?= t::get_forum_link(); ?>" target="_blanc"><img src="/images/frame/<?=t::iso()?>/forum_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/account.php"><img src="/images/frame/<?=t::iso()?>/options_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/player/invite"><img src="/images/frame/<?=t::iso()?>/invite_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/library.php"><img src="/images/frame/<?=t::iso()?>/library_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/servis.php"><img src="/images/frame/<?=t::iso()?>/service_normal.png" /></a></td>
                            </tr>
                            <tr>
                                <td><a href="/logout.php"><img src="/images/frame/<?=t::iso()?>/exit_normal.png" /></a></td>
                            </tr>
                        </table><!-- #vertical_button -->
                    </td>
                </tr>
                <tr>
                    <td class="vertical_menu_footer"></td>
                </tr>
            </table>
        </tr>
        <tr>
            <table id="banner_menu">
                <tr>
                    <td><div id="cable_vmenu_banner"></div></td>
                </tr>
                <tr>
                    <td class="banner_menu_header"></td>
                </tr>
                <tr>
                    <td class="banner_menu_main">
                        <table class="banner_border">
                            <tr>
                                <td class="banner_border_lt"></td>
                                <td class="banner_border_t"></td>
                                <td class="banner_border_rt"></td>
                            </tr>
                            <tr>
                                <td class="banner_border_l"></td>
                                <td class="banner_content">
                                    <div class="banner_detail_1"></div>
                                    <a href="/donate.php"><img width="95px" height="100px" src="/images/banners/<?= t::iso() ?>/donate1.png" title="<?=t::get('Купить крышки!')?>" alt="<?=t::get('Купить крышки!')?>"/></a>
                                </td>
                                <td class="banner_border_r"></td>
                            </tr>
                            <tr>
                                <td class="banner_border_lb"></td>
                                <td class="banner_border_b"></td>
                                <td class="banner_border_rb"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="banner_menu_divider"></td>
                </tr>
                <tr>
                    <td class="banner_menu_main">
                        <table class="banner_border">
                            <tr>
                                <td class="banner_border_lt"></td>
                                <td class="banner_border_t"></td>
                                <td class="banner_border_rt"></td>
                            </tr>
                            <tr>
                                <td class="banner_border_l"></td>
                                <td class="banner_content">
                                    <div class="banner_detail_2"></div>
                                    <img width="95px" height="100px" src="/images/banners/<?= t::iso() ?>/zaglushka.png"/>
                                </td>
                                <td class="banner_border_r"></td>
                            </tr>
                            <tr>
                                <td class="banner_border_lb"></td>
                                <td class="banner_border_b"></td>
                                <td class="banner_border_rb"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="banner_menu_divider"></td>
                </tr>
                <tr>
                    <td class="banner_menu_footer"></td>
                </tr>
            </table>
        </tr>
        <tr>
    </div>
    <div id="cable_vertical_main"></div>
    <div id="content">
        <table id="table_content">
            <tr>
                <td class="table_content_left_header"></td>
                <td class="table_content_center_header">
                    <a href="javascript:history.back();">
                        <img src="/img/back.png" align="left" id="back_button" alt="<?=t::get('Вернуться назад')?>"></a>
                    <div class="page_title"><marquee behavior="slide"><?= t::get($this->pageTitle) ?></marquee></div></td>
                <td class="table_content_right_header"></td>
            </tr>
            <tr>
                <td class="table_content_left_border"></td>
                <td>
                    <div id="inner_content">
	                    <?php
	                    if (Yii::app()->stat->model->email_confirmed == Players::NO){
		                    echo Window::error(Players::activationForm()).'<br>';
	                    }
	                    ?>
