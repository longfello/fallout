<?php
/* @var $this StatisticController */

$this->breadcrumbs=array(
	'Statistic'=>array('/admin/statistic'),
	'Ref',
);
?>

<style>
  .show-player i{
    margin-right: 5px;
  }
  .players {
    margin:0px;
    padding:0px;
    display: none;
    line-height: 1.2;
  }
  .players li{
    list-style-type: none;
  }
  :focus {
    outline: none;
  }
  .levels{
    display: none;
  }
  .btn-box-tool:focus{
    outline: none;
  }
  .overlay{
    display: none;
  }
  .overlay .fa-refresh{
    margin-top: 10px;
    margin-left: 500px;
  }
  td .box {
    margin-bottom: 0px;
    padding-bottom:0px;
  }
  .box .box-body {
    margin-top: 0px;
    padding-top: 0px;
  }
  .box .box-header{
    margin: 0px;
    padding: 0px;
  }
  .ref-table .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border-top: 1px solid #ddd;
    line-height: 0.3;
    padding: 8px;
    vertical-align: middle;
  }
</style>



<?php

function playersCount($id){
  $sql='SELECT COUNT(id) AS count_players FROM `players` WHERE ref='.$id;
  $players=Yii::app()->db->commandBuilder->createSqlCommand($sql)->queryAll();
  foreach($players as $value) {
    echo $value['count_players'];
  }
}

function playersRef($data){
?>
  <div class="box box-default collapsed-box">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo $data['user']; ?></h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool show-level" data-widget="collapse" data-uid="<?php echo $data['id']; ?>"><i class="fa fa-plus"></i></button>
      </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body levels">
    </div><!-- /.box-body -->
    <div class="overlay">
      <i class="fa fa-refresh fa-spin fa-2x"></i>
    </div>
  </div><!-- /.box -->
<?php
}

?><span class="ref-table"><?php
$this->widget('ext.yiiBooster.widgets.TbGridView', array(
  'dataProvider' => $dataProvider,
  //'enableSorting'=> false,
  'columns' => array(
    array(
      'header' => 'id',
      'name' => 'id',
      'type' => 'raw',
      'htmlOptions'=>array('width'=>'300px'),
    ),
    array(
      'header' => 'Nickname',
      'name' => 'user',
      'type' => 'raw',
      'value' => 'playersRef($data)',
    ),
    array(
      'header' => 'Refs',
      'name' => 'user',
      'type' => 'raw',
      'value' => 'playersCount($data["id"])',
      'htmlOptions'=>array('width'=>'300px'),
    ),
  ),
));
?>
</span>


<?php
Yii::app()->clientScript->registerScript("home-chart","

$(document).on('click','.show-player .fa-minus-circle', function(e){
    $(this).siblings('.players').toggle();
    $(this).parent().find('.fa').toggleClass('hidden');
});
$(document).on('click','.show-player .fa-plus-circle', function(e){
  $(this).parent().find('.fa').toggleClass('hidden');
  var uid = $(this).data('uid');
  var level = $(this).data('level');
    var self = this;
  $.post( '/admin/statistic/loadPlayer', {uid:uid,level:level}, function( data ) {
    $(self).siblings('.players').html(data).toggle();
  });
});

$(document).on('click', '.show-level', function(e){
  if ($(this).children().hasClass('fa-plus')) {
      $(this).children().removeClass('fa-plus').addClass('fa-minus');
      var uid = $(this).data('uid');
      var self = this;
      var res=$.post( '/admin/statistic/load', {uid:uid}, function( data ) {
          $(self).parent().parent().parent().find('.overlay').show();
          $(self).parent().parent().parent().find('.levels').html(data);
      });
      res.complete(function(){ $(self).parent().parent().parent().find('.overlay').hide(); });
    } else {
      $(this).children().removeClass('fa-minus').addClass('fa-plus');
    }
});

", CClientScript::POS_READY);
?>
