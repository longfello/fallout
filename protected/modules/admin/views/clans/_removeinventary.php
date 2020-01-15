<?php
/**
 *
 * @var \Players $model
 */

?>

<?= CHtml::beginForm(array($this->createUrl('removeItem')), 'post', array('class' => 'form-horizontal')) ?>
<?= CHtml::hiddenField('id', $model->id, array('class' => 'form-control')) ?>
<div class="form-group">
    <label class="col-sm-2 control-label">Предмет:</label>

    <div class="col-sm-10">
        <?= CHtml::textField('item_name','',array('class' => 'form-control', 'id' => 'items')); ?>
        <?= CHtml::hiddenField('itemId', '', array('id' => 'itemId')) ?>
        <span class="help-block">В наличии <span id="cur_items">0</span> шт.</span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Количество:</label>
    <div class="col-sm-10">
        <?= CHtml::numberField('count', '1', array('class' => 'form-control', 'min'=>1, 'id' => 'items_col')) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?= CHtml::submitButton('Изьять предмет', array('class' => 'btn btn-primary')) ?>
    </div>
</div>

<?= CHtml::endForm() ?>

<script type="text/javascript">
    $( function() {
        $('#items').autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: "/admin/api/autocomplete",
                    data: {
                        category: 'clanitem',
                        term: request.term,
                        clan_id: '<?= $model->id; ?>'
                    },
                    success: function( data ) {
                        response( data );
                    }
                } );
            },
            appendTo: '#ajaxModal',
            minLength: 2,
            select: function( event, ui ) {
                event.preventDefault();
                $("#items").val(ui.item.label);
                $("#itemId").val(ui.item.id);
                $("#cur_items").html(ui.item.value);
                $("#items_col").val(1);
                $("#items_col").attr('max', ui.item.value);
            }
        } );
    });
</script>
