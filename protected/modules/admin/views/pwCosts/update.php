<?php
$this->breadcrumbs=array(
	'Цены крышек'=>array('index'),
	$model->id,
	'Обновить',
);

	$this->menu=array(
	array('label'=>'Список цен','url'=>array('index')),
	array('label'=>'Создать цену','url'=>array('create')),
	);
	?>

	<h1>Обновление цены крышек <?php echo $model->id; ?></h1>

	<div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tmain" aria-controls="tmain" role="tab" data-toggle="tab">Основное</a></li>
			<li role="presentation">               <a href="#tbox"  aria-controls="tbox"  role="tab" data-toggle="tab">Ящики</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="tmain">
				<div class="well">
					<?php $this->renderPartial('_form', array(
						'model' => $model,
					)); ?>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="tbox">
				<div class="well box-content" data-cost-id="<?= $model->id ?>">

				</div>
			</div>
		</div>
	</div>

<?php $this->renderPartial('_help'); ?>