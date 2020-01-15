<?php
$this->breadcrumbs=array(
	'Nkr Prices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List NkrPrices','url'=>array('index')),
	array('label'=>'Create NkrPrices','url'=>array('create')),
	array('label'=>'View NkrPrices','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage NkrPrices','url'=>array('admin')),
	);
	?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>