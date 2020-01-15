<?php
$this->breadcrumbs = array(
	'Реферальные ссылки' => array( 'index' ),
	'Статистика',
);

$this->menu = array(
	array( 'label' => 'Добавить', 'url' => array( 'create' ) ),
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->getModule('admin')->getAssetsUrl() . '/js/plugins/chartjs/Chart.min.js', CClientScript::POS_END);
?>
<h3>Общая статистика</h3>

	<div class="row">
		<div class="col-md-12">
			<?php echo $this->renderPartial('_view_form',array(
				'model'   => $searchForm,
			)); ?>
		</div>
	</div>

	<br>

<?php echo $this->renderPartial('_view',array(
	'id'      => 'overall-stat',
	'regData' => $regData,
	'regOpen' => $regOpen,
	'regReg'  => $regReg,
)); ?>

<?php
  $models = ReferalLinks::model()->findAll();
  foreach ($models as $model) {
	  $query = "
select a.date, COALESCE(d.cnt, 0) register, COALESCE(c.cnt, 0) open
from (
    select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
        from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
        cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
        cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
        ) a
        left JOIN
      (
          SELECT count(*) cnt, DATE(dt) date
        FROM rev_referal_users rru
        WHERE rru.action = 'register' AND rru.link_id = {$model->id}
        GROUP BY DATE(dt)
) as d ON d.date = a.date
        left JOIN
      (
          SELECT count(*) cnt, DATE(dt) date
        FROM rev_referal_users rru
        WHERE rru.action = 'open' AND rru.link_id = {$model->id}
        GROUP BY DATE(dt)
) as c ON c.date = a.date
where a.date between '".date('c', $searchForm->beginTS)."' and '".date('c', $searchForm->endTS)."'
ORDER BY a.date";

	  $result   = Yii::app()->db->commandBuilder->createSqlCommand($query)->queryAll();

	  $regData  = array();
	  $regOpen  = array();
	  $regReg   = array();
	  foreach($result as $one) {
		  $regData[]  = $one['date'];
		  $regOpen[]  = $one['open'];
		  $regReg []  = $one['register'];
	  }

	  ?>

	  <h3>Cтатистика по ссылке <?php echo CHtml::link($model->name, $model->getLink()) ?></h3>

	  <?php
	  echo $this->renderPartial('_view',array(
		  'id'      => 'link-'.$model->id,
		  'regData' => $regData,
		  'regOpen' => $regOpen,
		  'regReg'  => $regReg,
	  ));
  }

?>
