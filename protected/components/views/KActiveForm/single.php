<?php
  /**
   * @var $editor string
   * @var $model CActiveRecord
   * @var $attribute string
   * @var $value string
   * @var $limit integer
   */

$el_name = $model->tableName().'-'.$attribute.'-ac';
$el_no = uniqid();
$el_id = $el_name.$el_no;


$values = [];
foreach($chainModels as $chainModel){
  $values[$chainModel->getAttribute($chainKey)] = $chainModel->getAttribute($chainValue);
};
?>

<div class="autocomplete-wrapper" id="<?= $el_id ?>">
  <ul class="items-list">
    <?php foreach($values as $key => $value){ ?>
        <li data-id="<?= $key ?>"><?= $value ?></li>
    <?php } ?>
  </ul>

  <?= CHtml::textField($el_name, '', [
    'class' => 'form-control ac-input'
  ])?>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#<?= $el_id ?> .ac-input').autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "/admin/api/autocomplete",
          data: {
            category: '<?= $chainModelName ?>',
            term: request.term,
            lang: <?= (int)$lang_id ?>
          },
          success: function( data ) {
            response(data);
          }
        } );
      },
      minLength: 1,
      select: function( event, ui ) {
        return selectItem(ui.item);
      }
    });

    $(document).on('click','#<?= $el_id ?> .items-list li a.btn-remove', function(e){
      e.preventDefault();
      $(this).parents('li').remove();
      genRealValue();
    });
    $(document).on('mouseover','#<?= $el_id ?> .items-list li', function(){
      if ($('a', this).size() == 0){
        $(this).append('<a href="#" class="btn-remove">x</a>');
      }
    });

    function selectItem(item){
      if ($('#<?= $el_id ?> .items-list li').size() > <?= $limit ?>-1){
        $('#<?= $el_id ?> .items-list li:last').remove();
      }
      $('#<?= $el_id ?> .items-list').append('<li data-id="'+item.id+'">'+item.value+'</li>');
      genRealValue();
      $('#<?= $el_id ?> .ac-input').val('');
      return false;
    }
    function genRealValue(){
      var val = [];
      $('#<?= $el_id ?> .items-list li').each(function(){
        var id = $(this).data('id');
        val.push(id);
      });
      var val = unique(val).join(',');
      console.log(val);
      $('#<?= $el_id ?>').parents('.input-group').find('.real-attribute').val(val);
    }

    function unique(arr) {
      var hash = {}, result = [];
      for ( var i = 0, l = arr.length; i < l; ++i ) {
        if ( !hash.hasOwnProperty(arr[i]) ) { //it works with objects! in FF, at least
          hash[ arr[i] ] = true;
          result.push(arr[i]);
        }
      }
      return result;
    }
  });
</script>