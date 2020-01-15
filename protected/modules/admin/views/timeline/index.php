<?php
$this->breadcrumbs = array(
  'Хроника' => array('index'),
);

$this->menu = array();
?>

<div class="search-form well">
  <?php $this->renderPartial('_search', array(
    'model' => $model,
  )); ?>
</div><!-- search-form -->
<div class="clearfix"></div>
<?php

$data = array();
$criteria = new CDbCriteria;
$criteria = new CDbCriteria();
$criteria->order = "created_at DESC";

if ($model->category) {
  $criteria->addCondition('category = :caregory');
  $criteria->params[':caregory'] = $model->category;
}
if ($model->event) {
  $criteria->addCondition('event = :event');
  $criteria->params[':event'] = $model->event;
}

$count = Timeline::model()->count($criteria);

$pages = new CPagination($count);
// элементов на страницу
$pages->pageSize = 10;
$pages->applyLimit($criteria);

foreach (Timeline::model()->findAll($criteria) as $one) {
  $data[] = array(
    'model' => $one
  );
}

$this->widget('ext.CdVerticalTimeLine.CdVerticalTimeLine', array(
    'events' => $data
  )
);

$this->widget('bootstrap.widgets.TbPager', array(
  'pages' => $pages,
))

?>

