<?php $this->beginContent('//layouts/main'); ?>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">

             <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/avatar4.png" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Привет, <?php echo ucfirst(Yii::app()->user->name); ?></p>
                            <a><i class="fa fa-circle text-success"></i> Онлайн</a>
                        </div>
                    </div>
                    <!-- search form -->
                  <!--   <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form> -->
                    <!-- /.search form -->

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?php $this->widget('bootstrap.widgets.TbMenu',array(
                        'type' => TbMenu::TYPE_PILLS,
                        'stacked' => true,
                        'encodeLabel' => false,
                        'items'=>array(
                                array('label'=>'<i class="fa fa-heartbeat"></i>Хроника', 'url'=>array('/admin/timeline'), 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('admin')),

                                array('url'=>'#', 'label'=>'<i class="fa fa-newspaper-o"></i>Контент', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('admin'), 'items' => array(
                                    array('label'=>'Новости', 'url'=>array('/admin/news/index')),
                                    array('label'=>'Страницы', 'url'=>array('/admin/pages/index')),
                                    array('label'=>'robots.txt', 'url'=>array('/admin/robots/index')),
                                )),
                                array('url'=>'#', 'label'=>'<i class="fa fa-gamepad"></i>Игра', 'visible'=> Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics')), 'items' => array(
                                    array('label'=>'Игроки', 'url'=>array('/admin/players'), 'visible' => (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics'))),
                                    array('label'=>'Кланы', 'url'=>array('/admin/clans'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Расы', 'url'=>array('/admin/playerRace/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Инвентарь', 'url'=>array('/admin/equipment/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Татуировки', 'url'=>array('/admin/tatoos/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Квесты', 'url'=>array('/admin/quest/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Подарки', 'url'=>array('/admin/presents/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Подарки кланам', 'url'=>array('/admin/clanPresents/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Мобы', 'url'=>array('/admin/npc/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Крафтинг', 'url'=>array('/admin/crafting/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Рецепты', 'url'=>array('/admin/recipes/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Аватарки чата', 'url'=>array('/admin/chatAvatarBase/index'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                )),
                                array('label'=>'<i class="fa fa-history"></i>Игровые логи', 'url'=>'#', 'visible' => Yii::app()->user->checkAccess('admin'), 'items' => array(
                                    array('label'=>'Банковские переводы', 'url'=>array('/admin/bankHistory'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Продажа крышек за золото', 'url'=>array('/admin/pmarketHistory'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Общий чат', 'url'=>array('/admin/commonChat'), 'visible' => Yii::app()->user->checkAccess('admin')),
                                    array('label'=>'Приглашения', 'url'=>array('/admin/inviteLog'), 'visible' => Yii::app()->user->checkAccess('admin'))
                                )),
                                array('url'=>'#', 'label'=>'<i class="fa fa-cog"></i>Инструменты', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('admin'), 'items' => array(
                                    array('label'=>'Очистить кэш', 'url'=>array('/admin/flush/index')),
                                    array('label'=>'Загрузка лабиринта', 'url'=>array('/admin/map/index')),
                                    array('label'=>'Бонусы в лабиринте', 'url'=>array('/admin/cavesbonus/index')),
                                    array('label'=>'Тест боевки', 'url'=>array('/admin/combat/index')),
                                    array('label'=>'Файловый менеджер', 'url'=>array('/admin/tools/manager')),
                                    array('label'=>'Настройки', 'url'=>array('/admin/settings/index')),
                                )),
                                array('url'=>'#', 'label'=>'<i class="fa fa-area-chart"></i>Статистика', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('admin'), 'items' => array(
                                    array('label'=>'Уровни игроков', 'url'=>array('/admin/statistic/leveling')),
                                    array('label'=>'Рефералы', 'url'=>array('/admin/statistic/ref')),
                                    array('label'=>'Рефералы по рекламе', 'url'=>array('/admin/statistic/adv')),
                                    array('label'=>'Реферальные ссылки', 'url'=>array('/admin/referal')),
                                )),
                                array('url'=>'#', 'label'=>'<i class="fa fa-language"></i>Локализация', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('translate'), 'items' => array(
                                    array('url'=>['/translate/admin'], 'label'=>'Переводы - игра', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('translate'),'itemOptions'=>array('class'=>'dropdown-submenu')),
                                    array('url'=>['/translate/home'], 'label'=>'Переводы - главная', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('translate'),'itemOptions'=>array('class'=>'dropdown-submenu')),
                                    array('url'=>['/translate/language/admin'], 'label'=>'Языки', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('admin'),'itemOptions'=>array('class'=>'dropdown-submenu')),
                                )),
                                array('url'=>'#', 'label'=>'<i class="fa fa-users"></i>Пользователи', 'visible'=> Yii::app()->authManager && (Yii::app()->user->checkAccess('admin') || Yii::app()->user->checkAccess('analytics')), 'items' => array(
                                    array('url'=>['/rbac'], 'label'=>'Роли и права', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess(Yii::app()->getModule('rbac')->rbacUiAdmin)),
                                    array('url'=>['/user/admin'], 'label'=>'Администраторы', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess(Yii::app()->getModule('rbac')->rbacUiAdmin)),
                                )),
                                array('url'=>'#', 'label'=>'<i class="fa fa-money"></i>Монетизация', 'visible'=> Yii::app()->authManager && Yii::app()->user->checkAccess('admin'), 'items' => array(
                                    array('label'=>'Цены крышек', 'url'=>array('/admin/PwCosts/index')),
                                    array('label'=>'Доллары НКР', 'url'=>array('/admin/nkrEvents/index')),
                                    array('label'=>'Оплаты', 'url'=>array('/admin/paymentwall/index')),
                                )),
                            ),
                    )); ?>
   
                </section>
          <!-- /.sidebar -->
        </aside>

        <aside class="right-side">

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> <?php echo $this->pageTitle; ?> </h1>

          <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
                'tagName'=>'ol',  
                'htmlOptions'=>array('class'=>'breadcrumb'),
                'homeLink'=>CHtml::tag('li', array(),CHtml::link('Главная', array('/admin')),true),
            )); ?><!-- breadcrumbs -->
          <?php endif?>

        </section>

        <?php if (isset($this->menu) && $this->menu) { ?>
          <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-10">
              <section class="content">
                <?php echo $content; ?>
              </section><!-- /.content -->
            </div>
            <div class="col-xs-4 col-md-2 sidebar">
              <p class="margin"></p>
              <?php
              $this->widget('bootstrap.widgets.TbMenu', array(
                  'type' => TbMenu::TYPE_BUTTONS,
                  'stacked' => true,
                  'items'=>$this->menu,
	                'encodeLabel'=> false
              ));
              ?>
            </div>
          </div>
        <?php } else { ?>
          <!-- Right side column. Contains the navbar and content of the page -->
            <!-- Main content -->
          <section class="content">
            <?php echo $content; ?>
          </section><!-- /.content -->

        <?php } ?>
        </aside><!-- /.right-side -->


<?php $this->endContent(); ?>