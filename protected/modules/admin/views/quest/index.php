<?php
$this->breadcrumbs = array(
	'Квесты' => array( 'index' ),
	'Управление',
);

$this->menu = array(
	array( 'label' => 'Добавить', 'url' => array( 'create' ) ),
);
?>

<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'quest-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'id',
	  'opis' => [
	    'name' => 'opis',
	    'type' => 'html'
    ],
		'minlev',
		'maxlev',
	  'questor' => [
	    'name' => 'questor',
	    'value' => '$data->getQuestorName()',
      'filter' => [
        Quest::QUESTOR_DAIZY => 'Дэйзи',
        Quest::QUESTOR_HUNTER => 'Логово охотников',
      ]
    ],
		[
			'name' => 'enabled',
			'type'  => 'raw',
			'value' => '$data->enabledDropdown',
			'filter' => [
				0 => 'Запрещен',
				1 => 'Разрешен'
			]
		],
		/*
		'npcs',
		'drop1',
		'drop2',
		'amount',
		'drop1_amount',
		'drop2_amount',
		'item',
		'itemcount',
		'plat',
		'gold',
		'q_check',
		'q_done',
		'qitem1',
		'qitem1count',
		'qrecipe1',
		'qrecipe1count',
		'qitem2',
		'qitem2count',
		'qrecipe2',
		'qrecipe2count',
		'qitem3',
		'qitem3count',
		'qrecipe3',
		'qrecipe3count',
		'qitem4',
		'qitem4count',
		'qrecipe4',
		'qrecipe4count',
		'experience',
		'pvp',
		*/
		array(
			'class' => 'booster.widgets.TbButtonColumn',
      'template' => '{update} {delete}'
		),
	),
) );



$url = $this->createUrl('quest/update');
Yii::app()->clientScript->registerScript('initEnabled',"
	$(document).on('change','select.enabled-state',function() {
        el = $(this);
        var data = {
          Quest: {
            enabled: el.val(),
          },
          ".Yii::app()->request->csrfTokenName.":'".Yii::app()->request->csrfToken."'
        };
        $.post('{$url}?id='+el.data('id'), data, function(){
          $(el).css('color', 'green').animate({'color': '#000'}, 500);
        });
    });", CClientScript::POS_READY);