<?php
/* @var $this PlayersController */
/* @var $model Players */

$this->breadcrumbs = array(
    'Игроки' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Редактирование',
);

$this->menu = array(
  /*	array('label'=>'Добавить игрока', 'url'=>array('create')), */
    array('label' => 'Перечень игроков', 'url' => array('index')),
    array('label' => 'Аналитика', 'url' => Yii::app()->createUrl("admin/players/analytics", array("id" => $model->id))),
);
?>

<h1>Игрок [<?php echo $model->id; ?>] <?= $model->user ?></h1>

<?php $this->widget('bootstrap.widgets.TbAlert'); // Yii::app()->user->getFlash('portCave') ?>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#person-editor" aria-controls="person-editor" role="tab"
                                              data-toggle="tab">Редактирование</a></li>
    <li role="presentation"><a href="#tele" aria-controls="person-tele" role="tab" data-toggle="tab">Телепортация</a>
    </li>
    <li role="presentation"><a href="#inventary" aria-controls="inventary" role="tab" data-toggle="tab">Добавить
        инвентарь</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="person-editor">
      <div class="well">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="tele">
      <div class="well">
        <div class="row">
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <?= CHtml::beginForm(array($this->createUrl('portCave')), 'post', array('class' => 'form-horizontal')) ?>
              <?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
              <?= CHtml::submitButton('Телепортировать в пещеры', array('class' => 'btn btn-primary')) ?>
              <?= CHtml::endForm() ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <?= CHtml::endForm() ?>
              <?= CHtml::beginForm(array($this->createUrl('portCity')), 'post', array('class' => 'form-horizontal')) ?>
              <?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
              <?= CHtml::submitButton('Телепортировать в город', array('class' => 'btn btn-primary')) ?>
              <?= CHtml::endForm() ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="inventary">
      <div class="well">
        <?php $this->renderPartial('_inventary', array('model' => $model)); ?>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#count").on('change blur keyup',function(){
      if($(this).val()<1) {
        $(this).parents('form').find("input[type='submit']").attr('disabled', 'disabled');
      } else {
        $(this).parents('form').find("input[type='submit']").removeAttr('disabled');
      }
    });
  });
</script>


