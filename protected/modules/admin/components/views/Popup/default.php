<?php $this->beginWidget(
    'booster.widgets.TbModal', array(
        'id' => 'esd-modal',
    )); ?>
<div class="modal-header" id="esd-modalHeader">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4></h4>
</div>
<div id="esd-modalContent"><?php $this->widget('application.modules.admin.components.Preloader', array('size' => Preloader::SIZE_LARGE)) ?>
<div class="content"></div></div>
<div class="modal-footer"><?= Popup::btnClose() ?></div>
<?php $this->endWidget(); ?>
