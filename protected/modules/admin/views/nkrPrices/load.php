<button class="btn btn-primary btnAddBox esdPopup" href="/admin/NkrPrices/addPrice?id=<?= $id ?>">Добавить цену</button>
<?php $this->widget( 'booster.widgets.TbGridView', array(
	'id'           => 'nkr-prices-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => array(
		'sum',
		'mobs',
		'bonus',
		array(
			'class' => 'booster.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
			'buttons'=>array
			(
				'update' => array
				(
					'label'=>'Редактировать',
					'icon' => 'glyphicon glyphicon-pencil',
					'url' => 'Yii::app()->createUrl("admin/NkrPrices/update", array("id"=>$data->id))',
					'options'=>array(
						'class'=>'esdPopup',
					),
				),
				'delete' => array
				(
					'label'=>'Удалить',
					'icon' => 'glyphicon glyphicon-trash',
					'url' => 'Yii::app()->createUrl("admin/NkrPrices/delete", array("id"=>$data->id))',
					'options'=>array(
						'class'=>'esdPopup',
					),
				),
			),
		),
	),
) ); ?>
