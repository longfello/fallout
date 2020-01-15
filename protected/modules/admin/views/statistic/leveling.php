<?php
/* @var $this StatisticController */

$this->breadcrumbs=array(
	'Статистика'=>array('/admin/statistic'),
	'По уровням',
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="chart">
      <canvas id="leveling-chart"></canvas>
    </div>
  </div><!-- /.box-body -->
</div>

<?php

Yii::app()->clientScript->registerScript("home-chart","
(function (){

        var areaChartCanvas = $('#leveling-chart').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
          labels: ".CJavaScript::encode($level).",
          datasets: [
            {
              label: 'Leveling',
              fillColor: 'rgba(210, 214, 222, 1)',
              strokeColor: 'rgba(210, 214, 222, 1)',
              pointColor: 'rgba(210, 214, 222, 1)',
              pointStrokeColor: '#c1c7d1',
              pointHighlightFill: '#fff',
              pointHighlightStroke: 'rgba(220,220,220,1)',
              data: ".CJavaScript::encode($cnt)."
            },
          ]
        };

        var areaChartOptions = {
          tooltipTemplate: '<%if (label){%>На <%=label%> уровне находится <%}%>игроков: <%= value %>',

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

})();
", CClientScript::POS_READY);