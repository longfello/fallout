<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Изъятие ресурсов</h4>
        </div>
        <div class="modal-body">
            <div class="ajax-form">
                <?php $this->renderPartial('_removeinventary', array('model' => $model)); ?>
                <div class="status"></div>
            </div>
            <div class="ajax-form">
                <?php $this->renderPartial('_removegold', array('model' => $model)); ?>
                <div class="status"></div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Закрыть</a>
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

        $('.ajax-form form').on('submit', function(e){
            e.preventDefault();
            var data = $(this).serializeArray();
            var self = this;
            $.post($(this).attr('action'), data, function(data){
                var status = $(self).parents('.ajax-form').find('.status');
                $(status).html(data);

                $("#items").val('');
                $("#itemId").val('');
                $("#cur_items").html(0);
                $("#items_col").val(1);
                $("#items_col").removeAttr('max');
                /*
                setTimeout(function(){
                    $(status).html('');
                }, 1500);
                */
            });
        });
    });
</script>