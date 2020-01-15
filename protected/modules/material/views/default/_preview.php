<?
/**
 * @var $new RNews
 */
?>

<?= CHtml::link(CHtml::image('/images/news/' . $new->img), $this->createUrl($viewName, array('id' => $new->id))) ?>
<h3><?= RText::truncateTextCount($new->title_news, 58, '...') ?></h3>
<p><?= RText::truncateTextCount($new->news, 120, '...') ?></p>
<div class="date"><?= date('d.m.Y', strtotime($new->date)) ?></div>
<div class="next"><a href="<?= $this->createUrl($viewName, array('id' => $new->id)) ?>"><?= t::get('Читать >>>') ?></a></div>