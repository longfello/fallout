<?php
/* @var $this PlayersController */
/* @var $model Players */

$this->breadcrumbs = array(
    'Игроки' => array('index'),
    'Управление',
);

$this->menu = array();
if (Yii::app()->user->checkAccess('admin')) {
  $this->menu[] = array('label' => 'Рассылка', 'url' => array('mail'));
}
if (Yii::app()->user->checkAccess('Модератор')) {
  $this->menu[] = array('label' => 'Список мультов', 'url' => array('mults'));
}

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#players-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

foreach(Yii::app()->user->getFlashes() as $key => $message) {
  echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
  <?php $this->renderPartial('_search', array(
      'model' => $model,
  )); ?>
</div><!-- search-form -->

<?php $this->widget('ext.yiiBooster.widgets.TbGridView', array(
    'id' => 'players-grid',
    'type' => TbHtml::GRID_TYPE_HOVER,
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'user',
        'level',
        'ip',
        array(
            'name' => 'banSite',
            'value' => '$data->getBanSite()?"Бан":""',
            'header' => 'Бан в игре',
            'filter' => CHtml::activeDropDownList($model, 'banSite', array(null => '', '1' => 'Да', '0' => 'Нет')),
        ),
        array(
            'name' => 'banChat',
            'value' => '$data->getBanChat()?"Бан":""',
            'header' => 'Бан в чате',
            'filter' => CHtml::activeDropDownList($model, 'banChat', array(null => '', '1' => 'Да', '0' => 'Нет')),
        ),
        array(
            'name' => 'banAdvert',
            'value' => '$data->getBanAdvert()?"Бан":""',
            'header' => 'Бан на объявлениях',
            'filter' => CHtml::activeDropDownList($model, 'banAdvert', array(null => '', '1' => 'Да', '0' => 'Нет')),
        ),
        array(
            'class' => 'booster.widgets.TbButtonColumn',
            'template' => '{give}&nbsp;{ban}&nbsp;{update}&nbsp;{delete}',
            'buttons' => array(
              'ban' => array(
                  'label' => 'Бан, контроль ботов',
                  'icon' => 'fa fa-crosshairs',
                  'url' => 'Yii::app()->createUrl("admin/players/analytics", array("id"=>$data->id))',
              ),
              'give' => array(
                  'label' => 'Выдача ресурсов',
                  'icon' => 'fa fa-gift',
                  'url' => 'Yii::app()->createUrl("admin/players/gift", array("id"=>$data->id))',
                  'click' => 'function(e) {
                                    $("#ajaxModal").remove();
                                    e.preventDefault();
                                    var $this = $(this)
                                      , $remote = $this.data("remote") || $this.attr("href")
                                      , $modal = $("<div class=\'modal\' id=\'ajaxModal\'><div class=\'modal-body\'><h5 align=\'center\'> <img src=\'' . Yii::app()->request->baseUrl . '/images/ajax-loader.gif\'>&nbsp;  Please Wait .. </h5></div></div>");
                                    $("body").append($modal);
                                    $modal.modal({backdrop: "static", keyboard: false});
                                    $modal.load($remote);
                                  }',
                'options' => array('data-toggle' => 'ajaxModal','style' => 'padding:4px;'),
                'visible' => "Yii::app()->user->checkAccess('admin')"
              ),
              'update' => array(
                'label' => 'Редактировать',
                'icon' => 'glyphicon glyphicon-pencil',
                'url' => 'Yii::app()->createUrl("admin/players/update", array("id"=>$data->id))',
                'visible' => "Yii::app()->user->checkAccess('admin')"
              ),
              'delete' => array(
                'label' => 'Удалить',
                'icon' => 'glyphicon glyphicon-trash',
                'url' => 'Yii::app()->createUrl("admin/players/delete", array("id"=>$data->id))',
                'visible' => "Yii::app()->user->checkAccess('admin')"
              ),
            ),
            'htmlOptions' => array('style' => 'width: 100px;text-align: left;'),
        ),
    ),
)); ?>
