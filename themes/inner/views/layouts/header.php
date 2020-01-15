<?
/**
 * @var $this Controller
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= t::iso(); ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?= $this->pageTitle ?></title>
<meta name="description" content="<?= $this->metaDescription ?>" />
<base href="<?= Yii::app()->request->getBaseUrl(true) ?>" />
<?php
    Yii::app()->clientScript->registerScriptFile('/js/show_inf.js', CClientScript::POS_HEAD);
?>
</head>
<body onLoad="on_load();" data-uid="<?= Yii::app()->stat->id ?>">
<div class="hint" id="hint1" style="z-index: 1005"></div><div class="hint" id="formmsg1" style="position:absolute; visibility:hidden;"></div>
<div id="wrapper">
<div id="cable_horizontal_main"></div>
<div id="left_side_bar">
    <table id="info_menu">
        <tr>
            <td class="info_menu_header"></td>
        </tr>
        <tr>
            <td class="info_menu_content">
                <div id="info_block">
                    <ul id="radioBox" class="radioBox"></ul>
                    <div id="lamp"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="info_menu_footer"></td>
        </tr>
    </table>
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
                        <td><a href="/#registration"><img src="/images/inner/<?=t::iso()?>/register.png" /></a></td>
                    </tr>
                    <tr>
                        <td><a href="/about"><img src="/images/inner/<?=t::iso()?>/about.png" /></a></td>
                    </tr>
                    <tr>
                        <td><a href="/new"><img src="/images/inner/<?=t::iso()?>/news.png" /></a></td>
                    </tr>
                    <tr>
                      <td><a href="/games"><img src="/images/frame/<?=t::iso()?>/games.png" /></a></td>
                    </tr>
                    <tr>
                        <td><a href="/article"><img src="/images/inner/<?=t::iso()?>/fallout.png" /></a></td>
                    </tr>
                    <tr>
                        <td><a href="/faq"><img src="/images/inner/<?=t::iso()?>/faq.png" /></a></td>
                    </tr>
                    <tr>
                        <td><a href="/newuser"><img src="/images/inner/<?=t::iso()?>/new_user.png" /></a></td>
                    </tr>
                    <tr>
                        <td><a href="/"><img src="/images/inner/<?=t::iso()?>/home.png" /></a></td>
                    </tr>
                </table><!-- #vertical_button -->
            </td>
        </tr>
        <tr>
            <td class="vertical_menu_footer"></td>
        </tr>
    </table>
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
                            <a href="almanah.php"><img width="95px" height="100px" src="/images/banners/<?= t::iso() ?>/b3.png" title="<?= t::encHtml('Альманах Пустоши (журнал)') ?>" alt="<?= t::encHtml('Альманах Пустоши (журнал)') ?>"/></a>
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
                            <a href="clans.php?view=clan_wars"><img width="95px" height="100px" src="/images/banners/<?= t::iso() ?>/b4.png" title="<?= t::encHtml('Клановые войны') ?>" alt="<?= t::encHtml('Клановые войны') ?>"/></a>
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
                <td class="banner_content">
                  <div class="banner_detail_2"></div>
                  <?php t::widget(); ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
            <td class="banner_menu_footer"></td>
        </tr>
    </table>
</div>
<div id="cable_vertical_main"></div>
<div id="content">
    <table id="table_content">
        <tr>
            <td class="table_content_left_header"></td>
            <td class="table_content_center_header">
                <a href="/">
                    <img src="/img/back.png" align="left" id="back_button" alt="<?= t::encHtml('Вернуться назад') ?>"></a>
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
