<?php
$this->breadcrumbs = array(
    'Кланы' => array('index'),
    'Управление',
);

$this->menu = array(
    array('label' => 'Создать клан', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('clans-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:none">
  <?php $this->renderPartial('_search', array(
      'model' => $model,
  )); ?>
</div><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'clans-grid',
    'dataProvider' => $model->search(),
    'type' => TbHtml::GRID_TYPE_HOVER,
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        'owner',
        'coowner',
        'gold',
        'platinum',
        'pass',
      /*
      'tag',
      'public_msg',
      'private_msg',
      'moneypass',
      'hospass',
      'clanwars',
      'chatroom',
      'clanstore',
      'clan_tax_ranges',
      'war_win',
      'union_win',
      */
        array(
            'class' => 'booster.widgets.TbButtonColumn',
            'template' => '{give}{remove}{update}{delete}',
            'buttons' => array(
                'give' => array(
                    'label' => 'Выдача ресурсов',
                    'icon' => 'fa fa-gift',
                    'url' => 'Yii::app()->createUrl("admin/clans/gift", array("id"=>$data->id))',
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
                'remove' => array(
                    'label' => 'Изъятие ресурсов',
                    'icon' => 'fa fa-eraser',
                    'url' => 'Yii::app()->createUrl("admin/clans/remove", array("id"=>$data->id))',
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
                )
            )
        ),
    ),
)); ?>
