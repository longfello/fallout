<?php
// Текущий аватар чата игрока
/**
 * @var chatAvatar $chatAvatar
 * @var Players $player
 * @var Appearance $appearance
 * @var array $data
 */

foreach(Yii::app()->user->getFlashes() as $key => $one) {
	if ($key == 'error') {
		echo Window::error($one);
	} else {
		echo Window::highlight($one);
	}
}

Yii::app()->clientScript->registerCssFile('/css/player-appearance.css');

$chatAvatarBase = ChatAvatarBase::model()->findByAttributes(['avatar_id' => $chatAvatar->avatar_id]);
$chat_avatar = ($chatAvatarBase && $chatAvatarBase->image) ? $chatAvatarBase->image : false;

$message = (!$appearance->free_set_used)?t::get('Внимание! Настройка внешности производится бесплатно лишь один раз. Все последующие изменения будут стоить 100 <img src=images/platinum.png />')
										:t::get('Данная услуга обойдется вам в 100 <img src=images/platinum.png />');

$show_race = strtotime($appearance->free_race_change_until) > time();
?>

<table class="appearance_block hidden">
	<tr>
		<td>
			<?php if (intval($appearance->unique_avatar) == 1 && file_exists(basedir."/avatars/players/unique/{$player->id}.png") && file_exists(basedir."/avatars/players/unique/hands/{$player->id}.png")) {
				echo '<div align="center"><div style="padding:0px;margin:0px;background-color:black;width:230px;height:230px;"><img src="/avatars/players/unique/'.$player->id.'.png"  border="0"><img src="/avatars/players/unique/hands/'.$player->id.'.png" border="0" style="padding:0px;margin:-230px 0px 0px 0px;display:block;"></div><br /><br />'.t::get('У вас установлен уникальный аватар').'</div></td></tr></table>';
			} else { ?>
			<div class="layout-preview-wrapper">
				<?php foreach($layouts as $layout) { ?>
					<img src="/images/null.gif" class="layout-preview layout-preview-<?= $layout->id ?>" />
				<?php } ?>
			</div>
		</td>
		<td>
			<div class="player-appearance">
				<div class="layout layout-race <?= $show_race?'':'disabled' ?>" data-layout='race' data-current="<?= $player->race_id ?>" <?= $show_race?"":"data-message='". (($player->race_id==PlayerRace::RACE_HUMAN)?t::encHtml('Для смены расы перейдите в городскую <a href="/implant.php">лабораторию</a>.'):t::encHtml('Наши ученые научились перерождать только людей.')) ."'"; ?>>
					<div class="title"><?= t::get('Раса') ?></div>
					<a href="#" class="prev-slide"><img src="/images/arrow-left.png "/></a>
					<div class="slider-wrapper">
						<div class="viewport">
						<ul class="slidewrapper custom">
							<?php foreach ($races as $race) { ?>
								<li data-id="<?= $race->id ?>"><?= $race->name ?></li>
							<?php } ?>
						</ul>
					</div>
					</div>
					<a href="#" class="next-slide"><img src="/images/arrow-right.png "/></a>
				</div>
				<div class="layout layout-gender" data-layout='gender' data-current="<?= (($player->gender == Players::GENDER_MALE)?'male':'female') ?>">
					<div class="title"><?= t::get('Пол') ?></div>
					<a href="#" class="prev-slide"><img src="/images/arrow-left.png "/></a>
					<div class="slider-wrapper">
						<div class="viewport">
							<ul class="slidewrapper custom">
								<li data-id="male"><?= t::get('мужской') ?></li>
								<li data-id="female"><?= t::get('женский') ?></li>
							</ul>
						</div>
					</div>
					<a href="#" class="next-slide"><img src="/images/arrow-right.png "/></a>
				</div>
				<?php foreach($layouts as $layout) { ?>
					<div class="layout layout-nested layout-<?= $layout->id ?>" data-layout='<?= $layout->id ?>' data-current="<?= $player->getAppearanceId($layout->id) ?>">
						<div class="title"><?= t::get($layout->name) ?></div>
						<a href="#" class="prev-slide"><img src="/images/arrow-left.png "/></a>
						<div class="slider-wrapper">
							<div class="viewport"><ul class="slidewrapper"></ul></div>
						</div>
						<a href="#" class="next-slide"><img src="/images/arrow-right.png "/></a>
					</div>
				<?php } ?>
			</div>

			<form action="/player/saveLayout" method="post" id="save-layout-form">
				<input data-message="<?= $message ?>" class="open_dialog_calculation" style="margin-left: 20px;" type='button' id="set_appearance_button" value="<?= t::encHtml('Сохранить изменения'); ?>" />
			</form>
		</td>
	</tr>
</table>

<?php

  Yii::app()->clientScript->registerScript('appearance', "
var app_editor = new window.__appearanceEditor(". CJavaScript::encode($data) .", ".CJavaScript::encode($player->attributes).", ".CJavaScript::encode($appearance->attributes).");
", CClientScript::POS_READY);

?>

<!-- Выбор аватора для чата -->
	<table class="chat_avatar_block">
		<tr>
			<td><img src="/images/chat/avatars/<?=$chat_avatar?>" id="chat_avatar" data-avatar-id="<?=$chatAvatar->avatar_id?>" /></td>
			<td class="choose_box" id="choose_box">
				<?= t::get('аватар в чате:') ?> <img src="/images/arrow-left.png" class="arrow left_arrow" data-arrow="left" /> <span id="chat_avatar_number" class="avatar_number"></span> <img class="arrow right_arrow" src="/images/arrow-right.png" data-arrow="right" />
				<form method="post">
					<input type="hidden" value="<?= $chatAvatar->avatar_id ?>" name="chat_avatar_pic" id="input_avatar_pic">
					<input type="submit" value="<?= t::encHtml('Сохранить изменения') ?>" id="chat_avatar_save_btn" class="save_btn">
				</form>
			</td>
		</tr>
	</table>
	<div class="clear"></div>
<?php  }


echo '<center>'.t::get('Всего можно набить 3 обычных, 3 клановых и 3 уникальных татуировки. У вас уже набиты:').'<br /><br />';
$unique_tatoo = array();
$unique_tatoo_res = _mysql_query("SELECT * FROM `tatoos` WHERE `owner` = {$player->id}");
if (_mysql_num_rows($unique_tatoo_res))
{
	echo '<p class="text-align: center;">'.t::get('Личные татуировки:').'</p>';
	echo '<div class="player-tatoo-wrapper">';
	while ($row = _mysql_fetch_assoc($unique_tatoo_res)) {
		$row = correct_tatoo($row);
		echo '<div class="player-tatoo">';
		echo game_print_tatoo_core($stat, $eq, $row['id'], '', $row['id']);
		echo '</div>';
	}
	echo '</div>';
}


$data = _mysql_exec("SELECT u.*, t.clan FROM utatoos u JOIN tatoos t ON u.tatoo = t.id WHERE u.owner = {$player->id} AND `t`.`owner` = 0 ORDER BY t.clan");
$tatoo_num = _mysql_num_rows($data);
$ii = 0;
if ($tatoo_num > 0) {
	echo '<br /><p class="text-align: center;">'.t::get('Обычные татуировки:').'</p>';
	echo '<div class="player-tatoo-wrapper">';
	while ($tatoo = _mysql_fetch_assoc($data)) {
		$tatoo = correct_tatoo($tatoo);
		if ($tatoo['clan'] != 0) $ii++;
		if ($ii == 1) echo '</div><br />'.t::get('Клановые татуировки').'<div class="player-tatoo-wrapper">';
		echo '<div class="player-tatoo">';
		echo game_print_tatoo_core($stat,$eq,$tatoo['tatoo'],'',$tatoo['tatoo']);

		if ($tatoo['lifetime'] > 0 && $tatoo['timeout'] == 0)
			echo t::get('До вечера').' <br />' . date('d.m.Y', $tatoo['lifetime']);
		elseif ($tatoo['lifetime'] > 0 && $tatoo['timeout'] == 1)
			echo '<span style="color:red">'.t::get('Действие окончено').'</span><br />&nbsp;';
		else
			echo '&nbsp;&nbsp;';

		echo '</div>';
	}
	echo '</div>';
}
echo t::get('Набить новую татуировку или свести существующую вы можете в <a href="/bar.php">баре</a>, подойдя к местному <a href="/tatoo.php">татуировщику Тулу</a>.');
echo '</center>';
?>
