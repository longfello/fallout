<?php
if (Yii::app()->user->checkAccess('admin') && (($post->active == 0) || (strtotime($post->date) > time()))) {
?>
  <div class="error">Данная страница отображается вам так как вы зашли под административным пользователем. Обычные пользователи данную страницу не увидят.</div>
<?php
}
?>

<div class="material">
    <? $this->renderPartial('_post', array('post' => $post)) ?>

    <ul class="list">
        <?php foreach($lastPosts as $new) : ?>
            <li><?= $this->renderPartial('_preview', array('new' => $new, 'viewName' => 'viewn')) ?></li>
        <?php endforeach ?>
    </ul>

</div>