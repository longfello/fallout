<?php
/**
 * @var $model PwCostsBox
 */
$this->breadcrumbs=array(
	'Бонусные ящики'=>array('index'),
	$model->name=>array('update','id'=>$model->id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
	array('label'=>'Управление','url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>

<?php
  $i = 0;
  foreach ($model->contents as $content){
	  $i++;
	  ?>
	  <div class="row">
		  <div class="col-md-12">
			  <!-- Danger box -->
			  <div class="box box-solid box-primary">
				  <div class="box-header">
					  <h5 class="box-title">Вариант <?=$i?> - <?= $content->name ?> <small class="label label-success"><?= $content->chance ?>%</small></h5>
					  <div class="box-tools pull-right">
						  <button class="btn btn-primary btn-sm esdPopup" href="/admin/PwCostsBox/updateContent?id=<?= $content->id ?>" ><i class="fa fa-pencil"></i></button>
						  <button class="btn btn-danger btn-sm esdPopup" href="/admin/PwCostsBox/deleteContent?id=<?= $content->id ?>"><i class="fa fa-times"></i></button>
					  </div>
				  </div>
				  <div class="box-body">
					  <?php
					    $n=0;
					    foreach ($content->items as $one){
						    ?>
						    <ul class="todo-list">
							    <li>
								    <span class="text"><?= $one->name ?></span>
								    <!-- Emphasis label -->
								    <small class="label label-success"><?= $one->chance ?>%</small>
								    <!-- General tools such as edit or delete-->
								    <div class="tools items-tools">
									    <a class="fa fa-edit esdPopup" href="/admin/PwCostsBox/updateItem?id=<?= $one->id ?>"></a>
									    <a class="fa fa-trash-o esdPopup" href="/admin/PwCostsBox/deleteItem?id=<?= $one->id ?>"></a>
								    </div>
							    </li>
						    </ul>
					        <?
					    }
						  if (!$content->items) {
							  ?><div class="alert alert-warning" role="alert">Вариант пуст!</div><?php
						  }
					  ?>
				  </div><!-- /.box-body -->
			  </div><!-- /.box -->
		  </div><!-- /.col -->
	  </div>
	  <?php
  }
  if (!$model->contents) {
	  ?><div class="alert alert-warning" role="alert">Ящик пуст!</div><?php
  }
?>
<button href="/admin/PwCostsBox/addContent?id=<?= $model->id ?>" class="btn btn-primary esdPopup"><i class="fa fa-plus"></i> Добавить вариант</button>

