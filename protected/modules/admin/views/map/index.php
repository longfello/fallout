<?php
/* @var $this MapController */

$this->breadcrumbs=array(
	'Загрузка лабиринта',
);
?>
  <div class="row">
    <div class="col-md-12">
      <?php $this->widget('bootstrap.widgets.TbAlert'); // Yii::app()->user->getFlash('portCave') ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="box box-default">
        <div class="box-header with-border">
          <i class="fa fa-map"></i>

          <h3 class="box-title">Схема лабиринта</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="alert alert-info alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-info"></i> Внимание!</h4>
            При загрузике файла схемы должен использоваться формат TXT. Символом <code>#</code> помечены стены, символом <code>.</code> помечены проходы.
          </div>

          <form class="form" enctype="multipart/form-data" method="post" action="<?= $this->createUrl('schema') ?>">
            <div class="form-group">
              <label for="schemaInputFile">Схема лабиринта</label>
              <input type="file" name="schema" id="schemaInputFile">
              <p class="help-block">Выберите файл для загрузки.</p>
            </div>
            <input type="submit" class="btn btn-primary" value="<?= t::get('Загрузить') ?>">
          </form>

        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->

    <div class="col-md-6">
      <div class="box box-default">
        <div class="box-header with-border">
          <i class="fa fa-map-o"></i>

          <h3 class="box-title">Картинка лабиринта</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="alert alert-info alert-dismissible">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-info"></i> Внимание!</h4>
            При загрузике файла картинки должен использоваться формат PNG. Изображение будет разбито на блоки 28х28 пикселей и применено к схеме лабиринта.
          </div>

          <?php if ($imageLoaded) { ?>
            <div class="alert alert-warning alert-dismissible">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
              <h4><i class="icon fa fa-info"></i> Внимание!</h4>
              Изображение карты уже загружено, если вы хотите загрузить новое изображение, надо удалить старое.
              <a href="<?= $this->createUrl('delete') ?>" class="btn btn-danger">Удалить старое изображение</a>
            </div>
          <?php } else { ?>
            <form class="form" enctype="multipart/form-data" method="post" action="<?= $this->createUrl('image') ?>">
              <div class="form-group">
                <label for="schemaInputFile">Картинка лабиринта</label>
                <input type="file" name="picture" id="schemaInputFile">
                <p class="help-block">Выберите файл для загрузки.</p>
              </div>
              <input type="submit" class="btn btn-primary" value="<?= t::get('Обработать') ?>">
            </form>
          <?php } ?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
<p>

</p>



<?php
/*
if (glob("images/labyrinth/*")) {
  ?>
  <p><?= t::get('') ?>:</p><br/>
  <form enctype="multipart/form-data" method="post">
    <input name="delete_img" type="submit" value="<?= t::get('Удалить старое изображение') ?>">
  </form>
  <?php
} else {
  ?>
  <form enctype="multipart/form-data" method="post">
    <?= t::get('Картинка лабиринта') ?>: <input name="picture" type="file">
    <input type="submit" value="<?= t::get('Загрузить') ?>">
  </form>
*/