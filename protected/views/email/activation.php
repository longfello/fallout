<h1 style="font-size: 20px;"><?= t::get('Активация аккаунта %s', [$model->user]); ?></h1>
<p style="font-size: 32px; margin-bottom: 15px"><?= t::get('Ваш код активации:'); ?> <?= $model->pass ?></p>
<a href="<?= Yii::app()->createAbsoluteUrl('/?activate='.$model->pass) ?>" style="font-size: 18px;"><?= t::get('Перейти в игру и активировать') ?></a>
