<?php
/**
 *
 * @var ReferalLinks $model
 */

?>

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Переходы / регистрации</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="chart">
					<canvas id="<?= $id ?>" style="height:250px"></canvas>
				</div>
				<div style="margin-bottom:10px; margin-right:30px; float:left;"><div style="height:20px; width:20px; background-color:red; float:left; margin-right:10px;"></div> - переходы </div>
				<div style="margin-bottom:10px; margin-right:30px; float:left;"><div style="height:20px; width:20px; background-color:rgba(0, 255, 0, 1); float:left; margin-right:10px;"></div> - регистрации </div>
				<div class="clearfix"></div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
		<?php
		if ($playersReg) {
			$this->widget('zii.widgets.grid.CGridView', array(
				'dataProvider'=>$playersReg,
				'columns' => array(
					array(
						'name'  => 'ID',
						'value' => '$data["id"]'
					),
					array(
						'name'  => 'Логин',
						'value' => '$data["user"]'
					),
					array(
						'name'  => 'Уровень',
						'value' => '$data["level"]'
					),
					array(
						'name'  => 'Купленные крышки',
						'value' => '$data["caps"]'
					)
				)
			));
		}
		?>
	</div>
</div>


<?php
Yii::app()->clientScript->registerScript("home-chart-".$id,"
(function($){

        var areaChartCanvas = $('#".$id."').get(0).getContext('2d');
        var areaChart = new Chart(areaChartCanvas);

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

        var areaChartData = {
            labels: ".CJavaScript::encode($regData).",
          datasets: [
            {
                fillColor: 'rgba(255,0,0,0.2)',
                strokeColor: 'rgba(255,0,0,1)',
                pointColor: 'rgba(255,0,0,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(255,0,0,1)',
              data: ".CJavaScript::encode($regOpen)."
            },
            {
                fillColor: 'rgba(0, 255, 0, 0.2)',
                strokeColor: 'rgba(0, 255, 0, 1)',
                pointColor: 'rgba(0, 255, 0, 1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(0, 255, 0, 1)',
              data: ".CJavaScript::encode($regReg)."
            }
          ]
        };

        areaChart.Line(areaChartData, areaChartOptions);

})(jQuery);
", CClientScript::POS_READY);

