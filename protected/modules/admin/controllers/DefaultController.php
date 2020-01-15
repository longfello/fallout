<?php

class DefaultController extends Controller
{

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';

  public function actionIndex()
  {
    if (Yii::app()->user->isGuest) {
        $this->render('login', array('model' => new LoginForm()));
    } else {
        $sql='
            select a.date, COALESCE(d.cnt, 0) cnt
            from (
                select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
                    from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
                    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
                    cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
                    ) a
                    left JOIN
                  (
                      SELECT count(id) cnt, DATE(FROM_UNIXTIME(reg_date)) date
                    FROM players
                    WHERE reg_date > UNIX_TIMESTAMP(NOW() - INTERVAL 1 month)
                    GROUP BY DATE(FROM_UNIXTIME(reg_date))
            ) as d ON d.date = a.date
            where a.date between NOW() - interval 1 month and NOW()
            ORDER BY a.date';
        $result   = Yii::app()->db->commandBuilder->createSqlCommand($sql)->queryAll();
        $regData  = $regCnt = array();
        foreach($result as $one) {
            $regData[] = $one['date'];
            $regCnt[]  = $one['cnt'];
        }

        $resourse=Resources::model()->findAll('date between NOW() - interval 1 month and NOW()');

        $date = $gold = $water = $platinum = $gold_clans = $bank = $platinum_clans = array();
        foreach($resourse as $val) {
            $date[] = $val['date'];
            $gold[]  = $val['gold'];
            $platinum[]  = $val['platinum'];
            $water[]  = $val['water'];
            $gold_clans[]  = $val['gold_clans'];
            $platinum_clans[]  = $val['platinum_clans'];
            $bank[]  = $val['bank'];
        }

        $loginData = $loginCnt = array();
        $login_sql='SELECT * FROM log_logins WHERE date between NOW() - interval 1 month and NOW()';
        $login_res   = Yii::app()->db->commandBuilder->createSqlCommand($login_sql)->queryAll();
        foreach($login_res as $login_data) {
            $loginData[] = $login_data['date'];
            $loginCnt[]  = $login_data['logins'];
        }

        $this->pageTitle = 'Хроника';
        $this->render('index', array(
            'regData' => $regData,
            'regCnt'  => $regCnt,
            'loginData' => $loginData,
            'loginCnt'  => $loginCnt,
            'date'  => $date,
            'gold'  => $gold,
            'water'  => $water,
            'platinum'  => $platinum,
            'bank'  => $bank,
            'gold_clans'  => $gold_clans,
            'platinum_clans'  => $platinum_clans,
        ));
    }
  }

  /**
   * Displays the login page
   */
  public function actionLogin()
  {
    $this->layout = "//layouts/mainLogin";
    $return = Yii::app()->session->get('referrer', '/admin');
    $model = new LoginForm;

    // if it is ajax validation request
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    // collect user input data
    if (isset($_POST['LoginForm'])) {
      $model->attributes = $_POST['LoginForm'];
      // validate user input and redirect to the previous page if valid
      if ($model->validate() && $model->login()) {
        // $this->redirect(Yii::app()->getModule('admin')->user->returnUrl);
        $this->redirect($return);
      }
    }
    // display the login form
    $this->render('login', array('model' => $model));
  }

  /**
   * Logs out the current user and redirect to homepage.
   */
  public function actionLogout()
  {
    Yii::app()->user->logout(false);
    $this->redirect('/');
  }

}