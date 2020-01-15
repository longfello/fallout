<div id="popup-message" title="<?= t::encHtml('На вас напали') ?>">
  <div class="popup-content"><?= t::get('Игрок %s напал на вас. Хотите управлять боем или включить автобой?', array($enemy->user)) ?></div>
</div>

<script type="text/javascript">

  $(document).ready(function(){
    var buttons_src = {
      '<?=t::encJs('Управлять', t::ESCAPE_SINGLE)?>'  : 'combatManual',
      '<?=t::encJs('Автобой', t::ESCAPE_SINGLE)?>'    : 'combatAutomove'
    };
    buttons = {};
    for(var i in buttons_src){
      (function(foo){
        buttons[i] = function(){
          $('body').trigger(foo);
        }
      })(buttons_src[i]);
    }

    $( "#popup-message" ).dialog({
      modal: true,
      buttons: buttons
    });
  });

</script>