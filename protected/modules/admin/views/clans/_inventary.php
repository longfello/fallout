<?php
/**
 *
 * @var \Players $model
 */

?>

<?= CHtml::beginForm(array($this->createUrl('addItem')), 'post', array('class' => 'form-horizontal')) ?>
<?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
<div class="form-group">
  <label class="col-sm-2 control-label">Предмет:</label>

  <div class="col-sm-10">
    <?= CHtml::textField('item_name','',array('class' => 'form-control', 'id' => 'items')); ?>
    <?= CHtml::hiddenField('itemId', '', array('id' => 'itemId')) ?>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-2 control-label">Количество:</label>
  <div class="col-sm-10">
    <?= CHtml::numberField('count', '1', array('class' => 'form-control', 'min'=>1)) ?>
  </div>
</div>

<div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">
    <?= CHtml::submitButton('Выдать предмет', array('class' => 'btn btn-primary')) ?>
  </div>
</div>

<?= CHtml::endForm() ?>

<script type="text/javascript">
  $( function() {
    $( "#items" ).autocomplete({
      source: "<?= $this->createUrl('autocompleteItem'); ?>",
      type: 'json',
      appendTo: '#ajaxModal',
      select: function( event, ui ) {
        event.preventDefault();
        $("#items").val(ui.item.label);
        $("#itemId").val(ui.item.value);
      }
    });
  });
</script>
