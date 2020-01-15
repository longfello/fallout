<?php
$this->breadcrumbs=array(
	'Ивенты с долларами НКР'=>array('index'),
	$model->name,
	'Изменение',
);

	$this->menu=array(
	array('label'=>'Список ивентов','url'=>array('index')),
	array('label'=>'Создать ивент','url'=>array('create')),
	);
	?>

	<h1>Обновление ивента <?php echo $model->event_id; ?></h1>

	<div>
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tmain" aria-controls="tmain" role="tab" data-toggle="tab">Основное</a></li>
			<li role="presentation">               <a href="#tprice"  aria-controls="tprice"  role="tab" data-toggle="tab">Прайс долларов НКР</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="tmain">
				<div class="well">
					<?php $this->renderPartial('_form',array('model'=>$model)); ?>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="tprice">
				<div class="well price-content" data-event-id="<?= $model->event_id ?>">

				</div>
			</div>
		</div>
	</div>

