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
  $this->menu[] = array('label' => 'Выдача предметов', 'url' => array('gifts'));
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

<?php if (Yii::app()->authManager && Yii::app()->user->checkAccess('admin')) { ?>
<?php echo CHtml::link('Расширенный поиск', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
  <?php $this->renderPartial('_search', array(
      'model' => $model,
  )); ?>
</div><!-- search-form -->
<?php } ?>

<?php
$columns = array();
if (Yii::app()->authManager && Yii::app()->user->checkAccess('admin')) {
    $columns = array(
        'id',
        'user',
        'email',
        'level',
        'gold',
        'platinum',
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
            'template' => '{give} &nbsp; {remove} &nbsp; {ban} &nbsp; {update} &nbsp; {delete} &nbsp {login}',
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
                    'options' => array('data-toggle' => 'ajaxModal'),
                    'visible' => "Yii::app()->user->checkAccess('admin')"
                ),
                'remove' => array(
                    'label' => 'Изъятие ресурсов',
                    'icon' => 'fa fa-eraser',
                    'url' => 'Yii::app()->createUrl("admin/players/remove", array("id"=>$data->id))',
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
                    'options' => array('data-toggle' => 'ajaxModal'),
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
                'login' => array(
                    'label' => 'Войти',
                    'icon' => 'fa fa-user-circle-o',
                    'url' => 'Yii::app()->createUrl("admin/players/login", array("id"=>$data->id))',
                    'visible' => "Yii::app()->user->checkAccess('admin')"
                ),
            ),
            'htmlOptions' => array('style' => 'width: 100px;text-align: left;'),
        ),
    );
} else {
    if (Yii::app()->authManager && (Yii::app()->user->checkAccess('analytics')||Yii::app()->user->checkAccess('user_teleportation'))) {
        $columns = array(
            'id',
            array(
                'class' => 'booster.widgets.TbButtonColumn',
                'template' => '{update}{ban}',
                'buttons' => array(
                    'update' => array(
                        'label' => 'Панель телепортации',
                        'icon' => 'glyphicon glyphicon-pencil',
                        'url' => 'Yii::app()->createUrl("admin/players/update", array("id"=>$data->id))',
                        'visible' => "Yii::app()->authManager && Yii::app()->user->checkAccess('user_teleportation')"
                    ),
                    'ban' => array(
                        'label' => ((Yii::app()->authManager && Yii::app()->user->checkAccess('analytics_mult'))?'Бан, мульты':'Бан'),
                        'icon' => 'fa fa-crosshairs',
                        'url' => 'Yii::app()->createUrl("admin/players/analytics", array("id"=>$data->id))',
                        'visible' => "Yii::app()->authManager && Yii::app()->user->checkAccess('analytics')"
                    )
                ),
                'htmlOptions' => array('style' => 'width: 100px;text-align: left;'),
            ),
        );
    }
}
?>

<?php $this->widget('booster.widgets.TbGridView', array(
    'id' => 'players-grid',
    'type' => TbHtml::GRID_TYPE_HOVER,
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => $columns,
)); ?>
