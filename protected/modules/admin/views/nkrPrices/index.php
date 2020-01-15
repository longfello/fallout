<?php
$this->breadcrumbs=array(
	'Nkr Prices',
);

$this->menu=array(
array('label'=>'Create NkrPrices','url'=>array('create')),
array('label'=>'Manage NkrPrices','url'=>array('admin')),
);
?>

<h1>Nkr Prices</h1>

<?php $this->widget('booster.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>
