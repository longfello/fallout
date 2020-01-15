<?php
/**
 *
 * @var DefaultController $this
 */
?>

<ul class="list">
    <?php foreach($news as $new) : ?>
        <li><?= $this->renderPartial('_preview', array('new' => $new, 'viewName' => 'viewg')) ?></li>
    <?php endforeach ?>
</ul>

<div class="pagination">
<? $this->widget('RLinkPager',array(
    'pages' => $pagination,
    'maxButtonCount' => (ceil($pagination->itemCount / $pagination->pageSize)),
    'header' => false,
)) ?>
</div>