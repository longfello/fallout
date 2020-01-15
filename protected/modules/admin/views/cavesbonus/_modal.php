<!-- Modal -->
<?=CHtml::form($this->createUrl('cavesbonus/savebonusbyajax', array('pointId' => $pointId)), 'post', array('id' => 'saveBonus', 'data-id' => $pointId)); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?= t::get('Добавление / Редактирование бонуса') ?></h3>
</div>

<div class="modal-body">

<div class="row">
    <table class="table table-bordered table-condensed table-hover">
        <tr>
            <td><?php echo CHtml::activeLabel($model,'gold'); ?></td>
            <td><?php echo CHtml::activeTextField($model,'gold'); ?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model,'platinum'); ?></td>
            <td><?php echo CHtml::activeTextField($model,'platinum'); ?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model,'item_id'); ?></td>
            <td><?php echo CHtml::activeTextField($model,'item_id'); ?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model,'item_count'); ?></td>
            <td><?php echo CHtml::activeTextField($model,'item_count'); ?></td>
        </tr>
    </table>
</div>


<div class="row buttons">
    <?php //echo CHtml::ajaxSubmitButton("submit", $this->createUrl('site/test'), array('success' => 'function() { alert("12345"); }')) ?>
    <?php //echo CHtml::ajaxButton('Сохранить', $this->createUrl('site/test'), array('success' => 'function() { alert("12345"); }')) ?>
</div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn"><?= t::get('Закрыть') ?></button>
    <button type="submit" class="btn btn-primary"><?= t::get('Сохранить') ?></button>
</div>
<?=CHtml::endForm(); ?>
