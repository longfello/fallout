<?php
Yii::app()->setLanguage('ru');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<!-- bootstrap 3.0.2 -->
	<!-- <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- font Awesome -->
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<!-- Ionicons -->
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<!-- Theme style -->
	<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- Style custom -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
          <![endif]-->


 	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body class="skin-blue">
    <?php
      $criteria = new CDbCriteria();
      $criteria->order = "created_at DESC";
      $criteria->addCondition("DATE(FROM_UNIXTIME(created_at)) = DATE(NOW())");
      $notifications = Timeline::model()->findAll($criteria);
      $error_count = Errors::model()->count();
    ?>
    <?php
        $items = array();

        if (Yii::app()->authManager && Yii::app()->user->checkAccess('admin')) {
            $items[] =  array(
                'class'=>'Notifications',
                'htmlOptions'=>array('class'=>'dropdown notifications-menu'),
                'icon'=>'fa fa-asterisk',
                'itemsCssClass'=>'dropdown-menu',
                'labelHeader'=>array('title'=>'Административные функции'),
                'labelFooter'=>array('title'=>'Перейти к настройкам'),
                'redirect' => '/admin/settings',
//                'badge'=>array('class'=>'label label-warning', 'value'=> count($notifications)),
                'items'=>array(
                   array('url'=>'/admin/flush/cache','icon'=>'ion ion-loop info','value'=>'Очистить кеш'),
                   array('url'=>'/admin/flush/assets','icon'=>'ion ion-refresh info','value'=>'Очистить ресурсы'),
                   array('url'=>'/admin/flush/all','icon'=>'ion ion-refresh info','value'=>'Очистить кеш и ресурсы'),
                )
            );

            $items[] =  array(
                'class'=>'Notifications',
                'htmlOptions'=>array('class'=>'dropdown notifications-menu'),
                'icon'=>'fa fa-newspaper-o',
                'itemsCssClass'=>'dropdown-menu',
                'labelHeader'=>array('title'=>'За сегодня зарегистрировано событий: {n}', 'params' => count($notifications)),
                'labelFooter'=>array('title'=>'Смотреть'),
                'redirect' => '/admin/timeline',
                'badge'=>array('class'=>'label label-warning', 'value'=> count($notifications)),
                'items'=>array(
//                            array('url'=>'#','icon'=>'ion ion-ios7-people info','value'=>'Why not buy a new awesome theme?')
                )
            );

            $items[] =  array(
                'class'=>'Notifications',
                'htmlOptions'=>array('class'=>'dropdown notifications-menu'),
                'icon'=>'fa fa-warning',
                'itemsCssClass'=>'dropdown-menu',
                'labelHeader'=>array('title'=>'Ошибок: {n}', 'params' => $error_count),
                'labelFooter'=>array('title'=>'Смотреть'),
                'redirect' => '/admin/errors',
                'badge'=>array('class'=>'label label-danger', 'value'=> $error_count),
                'items'=>array(
//                            array('url'=>'#','icon'=>'ion ion-ios7-people info','value'=>'Why not buy a new awesome theme?')
                )
            );
        }

        $items[] =  array(
            'class'=>'Users',
            'htmlOptions'=>array('class'=>'dropdown user'),
            'icon'=>'glyphicon glyphicon-user',
            'itemsCssClass'=>'dropdown-menu',
            'items'=>array(
//                            array('url'=>'#', 'label'=>'Сменить пароль'),
                array('url'=>array('//admin/default/logout'), 'label'=>'Выйти')
            )
        );

        $this->widget('Navbar', array(
            //'brand'=>CHtml::image(Yii::app()->baseUrl . "/images/logo_neocds.png", ""),
            'brand'=>'Rev-online',
            'brandUrl'=>$this->createUrl('default/index'),
            'htmlOptions'=>array('class'=>'navbar-right'),
            'items'=> $items
            )
        );
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
	   <?php echo $content; ?>
    </div><!-- ./wrapper -->
	<?php $this->widget('application.modules.admin.components.Popup') ?>

    <!-- Bootstrap -->
    <!-- <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/AdminLTE/app.js" type="text/javascript"></script>

</body>
</html>
