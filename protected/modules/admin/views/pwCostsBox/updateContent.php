<?php
/**
 * @var $model PwCostsContent
 */
echo $this->renderPartial('createContent',array('model'=>$model)); ?>

<div class="box box-solid box-primary">
	<div class="box-header">
		<h5 class="box-title">Содержимое</h5>
	</div>
	<div class="box-body">
	<?php
	    $n=0;
	    foreach ($model->items as $one){
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
		  if (!$model->items) {
			  ?><div class="alert alert-warning" role="alert">Содержимое отсутствует!</div><?php
		  }
	  ?>
	</div>
</div><!-- /.box-body -->
<button href="/admin/PwCostsBox/addItem?id=<?= $model->id ?>" class="btn btn-primary esdPopup"><i class="fa fa-plus"></i> Добавить содержимое</button>
