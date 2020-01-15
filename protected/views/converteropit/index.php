<?
/**
 * @var $this CController
 */
?>
<div class="container">
    <div class="jumbotron text-center">
        <h2><?= t::get('Конвертация данных из таблицы `players` поля `opit`') ?></h2>
        <p><?= t::get('Данные будут сконвертированы в таблицу `rev_experience_worker` в поле `garbage`') ?></p>
        <? if (Yii::app()->user->hasFlash('convertedSuccess')): ?>
            <div class="alert alert-success"><?= Yii::app()->user->getFlash('convertedSuccess') ?></div>
        <? endif ?>
        <p><a href="<?= $this->createUrl('convert') ?>" class="btn btn-primary btn-lg" role="button"><?= t::get('Конвертировать') ?></a></p>
    </div>
</div>
