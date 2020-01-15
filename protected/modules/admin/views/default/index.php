<?php
/* @var $this DefaultController */

Yii::app()->clientScript->registerScriptFile(Yii::app()->getModule('admin')->getAssetsUrl() . '/js/plugins/chartjs/Chart.min.js', CClientScript::POS_END);
$this->breadcrumbs = array(
    $this->module->id,
);

if (Yii::app()->user->checkAccess('admin')) {

?>
<div class="row">
    <div class="col-md-7">
      <?php $data = Timeline::getData() ?>
      <p>
        Отображены последние <?= count($data) ?> событий.
        <a href="<?= $this->createUrl('timeline/index') ?>" class="btn btn-default btn-xs">
          <i class="fa fa-heartbeat"></i> Смотреть все
        </a>
      </p>
      <?php
      $this->widget('ext.CdVerticalTimeLine.CdVerticalTimeLine', array(
              'events' => $data
          )
      );?>
    </div>
    <div class="col-md-5">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Количество игроков онлайн</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="user_logins" style="height:250px"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Количество регистраций</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="user" style="height:250px"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Вода</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="water" style="height:250px"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Золото</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="gold" style="height:250px"></canvas>
                </div>
                <div style="margin-bottom:10px; margin-right:30px; float:left;"><div style="height:20px; width:20px; background-color:red; float:left; margin-right:10px;"></div> - Золото у игроков </div>
                <div style="margin-bottom:10px; margin-right:30px; float:left;"><div style="height:20px; width:20px; background-color:rgba(0, 255, 0, 1); float:left; margin-right:10px;"></div> - Золото у кланов </div>
                <div style="margin-bottom:10px; margin-right:30px;"><div style="height:20px; width:20px; background-color:rgba(151,187,205,1); float:left; margin-right:10px;"></div> - Золото в банке </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Крышки</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="platinum" style="height:250px"></canvas>
                </div>
                <div style="margin-bottom:10px; margin-right:30px; float:left;"><div style="height:20px; width:20px; background-color:rgba(210, 214, 222, 1); float:left; margin-right:10px;"></div> - Крышек у игроков </div>
                <div style="margin-bottom:10px; margin-right:30px;"><div style="height:20px; width:20px; background-color:rgba(151,187,205,1); float:left; margin-right:10px;"></div> - Крышек у кланов </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>
</div>

<?php

Yii::app()->clientScript->registerScript("home-chart","
(function (){

        var areaChartCanvas = $('#user_logins').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ".CJavaScript::encode($loginData).",
          datasets: [
            {
                label: 'Electronics',
              fillColor: 'rgba(210, 214, 222, 1)',
              strokeColor: 'rgba(210, 214, 222, 1)',
              pointColor: 'rgba(210, 214, 222, 1)',
              pointStrokeColor: '#c1c7d1',
              pointHighlightFill: '#fff',
              pointHighlightStroke: 'rgba(220,220,220,1)',
              data: ".CJavaScript::encode($loginCnt)."
            },
          ]
        };

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 5,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
        };

        areaChart.Line(areaChartData, areaChartOptions);
        

        var areaChartCanvas = $('#user').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ".CJavaScript::encode($regData).",
          datasets: [
            {
                label: 'Electronics',
              fillColor: 'rgba(210, 214, 222, 1)',
              strokeColor: 'rgba(210, 214, 222, 1)',
              pointColor: 'rgba(210, 214, 222, 1)',
              pointStrokeColor: '#c1c7d1',
              pointHighlightFill: '#fff',
              pointHighlightStroke: 'rgba(220,220,220,1)',
              data: ".CJavaScript::encode($regCnt)."
            },
          ]
        };

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 5,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
        };

        areaChart.Line(areaChartData, areaChartOptions);





        var areaChartCanvas = $('#gold').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ".CJavaScript::encode($date).",
          datasets: [
            {
                label: 'Bank',
                fillColor: 'rgba(151,187,205,0.2)',
                strokeColor: 'rgba(151,187,205,1)',
                pointColor: 'rgba(151,187,205,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(151,187,205,1)',
              data: ".CJavaScript::encode($bank)."
            },
            {
                label: 'Gold_clans',
                fillColor: 'rgba(0, 255, 0, 0.2)',
                strokeColor: 'rgba(0, 255, 0, 1)',
                pointColor: 'rgba(0, 255, 0, 1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(0, 255, 0, 1)',
              data: ".CJavaScript::encode($gold_clans)."
            },
            {
                label: 'Gold',
                fillColor: 'rgba(255,0,0,0.2)',
                strokeColor: 'rgba(255,0,0,1)',
                pointColor: 'rgba(255,0,0,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(255,0,0,1)',
              data: ".CJavaScript::encode($gold)."
            },
          ]
        };

        areaChart.Line(areaChartData, areaChartOptions);





        var areaChartCanvas = $('#platinum').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ".CJavaScript::encode($date).",
          datasets: [
            {
                label: 'Platinum',
                fillColor: 'rgba(220,220,220,0.2)',
                strokeColor: 'rgba(220,220,220,1)',
                pointColor: 'rgba(220,220,220,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
              data: ".CJavaScript::encode($platinum)."
            },
            {
                label: 'Platinum_clans',
                fillColor: 'rgba(151,187,205,0.2)',
                strokeColor: 'rgba(151,187,205,1)',
                pointColor: 'rgba(151,187,205,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(151,187,205,1)',
              data: ".CJavaScript::encode($platinum_clans)."
            },
          ]
        };

        areaChart.Line(areaChartData, areaChartOptions);




        var areaChartCanvas = $('#water').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
            labels: ".CJavaScript::encode($date).",
          datasets: [
            {
                label: 'Water',
                fillColor: 'rgba(151,187,205,0.2)',
                strokeColor: 'rgba(151,187,205,1)',
                pointColor: 'rgba(151,187,205,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(151,187,205,1)',
              data: ".CJavaScript::encode($water)."
            },
          ]
        };

        areaChart.Line(areaChartData, areaChartOptions);




})();
", CClientScript::POS_READY);

} else {
    $this->pageTitle = 'Административная часть';
    ?>
        <div class="row">
            <div class="col-md-12">Добро пожаловать в административную часть</div>
        </div>
    <?php
}

?>

