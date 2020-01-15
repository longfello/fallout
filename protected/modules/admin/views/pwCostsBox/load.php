<button class="btn btn-primary btnAddBox esdPopup" href="/admin/PwCostsBox/addBox?id=<?= $id ?>">Добавить ящик</button>
<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'pw-costs-box-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'image' => [
			'name' => '',
			"type" => "raw",
			"filter" => false,
			'value' => '"<img src=\"".$data->getImageUrl()."\" style=\"max-height:64px; max-width: 64px;\">"',
		],
		'image_full' => [
			'name' => '',
			"type" => "raw",
			"filter" => false,
			'value' => '"<img src=\"".$data->getImageFullUrl()."\" style=\"max-height:64px; max-width: 64px;\">"',
		],
		'name',
		'chance',
		array(
			'class' => 'booster.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
			'buttons'=>array
			(
				'update' => array
				(
					'label'=>'Редактировать',
					'icon' => 'glyphicon glyphicon-pencil',
					'url' => 'Yii::app()->createUrl("admin/PwCostsBox/update", array("id"=>$data->id))',
					'options'=>array(
						'class'=>'esdPopup',
					),
				),
				'delete' => array
				(
					'label'=>'Удалить',
					'icon' => 'glyphicon glyphicon-trash',
					'url' => 'Yii::app()->createUrl("admin/PwCostsBox/delete", array("id"=>$data->id))',
					'options'=>array(
						'class'=>'esdPopup',
					),
				),
			),
		),
	),
) ); ?>
