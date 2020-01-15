<?php

class ReferalController extends Controller {
	public $layout = '//layouts/column2';
	public $pageTitle = 'Управление реферальными ссылками';

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array( 'index', 'delete', 'create', 'update', 'view', 'stat' ),
				'roles'   => array( 'admin' ),
			),
			array(
				'deny',  // deny all users
				'users' => array( '*' ),
			),
		);
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model = new ReferalLinks;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

		if ( isset( $_POST['ReferalLinks'] ) ) {
			$model->attributes = $_POST['ReferalLinks'];
			if ( $model->save() ) {
				if ($model->slug=='') {
					$model->slug=$model->id;
					$model->save();
				}
				$this->redirect( array( 'index') );
			}
		}

		$this->render( 'create', array(
			'model' => $model,
		) );
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate( $id ) {
		$model = $this->loadModel( $id );

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

		if ( isset( $_POST['ReferalLinks'] ) ) {
			$model->attributes = $_POST['ReferalLinks'];
			if ( $model->save() ) {
				if ($model->slug=='') {
					$model->slug=$model->id;
					$model->save();
				}
				$this->redirect( array( 'index') );
			}
		}

		$this->render( 'update', array(
			'model' => $model,
		) );
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 *
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete( $id ) {
		if ( Yii::app()->request->isPostRequest ) {
// we only allow deletion via POST request
			$this->loadModel( $id )->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if ( ! isset( $_GET['ajax'] ) ) {
				$this->redirect( isset( $_POST['returnUrl'] ) ? $_POST['returnUrl'] : array( 'index' ) );
			}
		} else {
			throw new CHttpException( 400, 'Invalid request. Please do not repeat this request again.' );
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionIndex() {
		$model = new ReferalLinks( 'search' );
		$model->unsetAttributes();  // clear any default values
		if ( isset( $_GET['ReferalLinks'] ) ) {
			$model->attributes = $_GET['ReferalLinks'];
		}

		$searchForm = new ReferalViewForm();


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
        WHERE rru.action = 'register'
        GROUP BY DATE(dt)
) as d ON d.date = a.date
        left JOIN
      (
          SELECT count(*) cnt, DATE(dt) date
        FROM rev_referal_users rru
        WHERE rru.action = 'open'
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

		$this->render( 'index', array(
			'searchForm' => $searchForm,
			'model'   => $model,
			'regData' => $regData,
			'regOpen' => $regOpen,
			'regReg'  => $regReg,
		) );
	}
	public function actionStat() {
		$searchForm = new ReferalViewForm();


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
        WHERE rru.action = 'register'
        GROUP BY DATE(dt)
) as d ON d.date = a.date
        left JOIN
      (
          SELECT count(*) cnt, DATE(dt) date
        FROM rev_referal_users rru
        WHERE rru.action = 'open'
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

		$this->render( 'stat', array(
			'searchForm' => $searchForm,
			'regData' => $regData,
			'regOpen' => $regOpen,
			'regReg'  => $regReg,
		) );
	}
	/**
	 * Manages all models.
	 */
	public function actionView($id) {
		$model = $this->loadModel( $id );

		$searchForm = new ReferalViewForm();


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
        WHERE rru.action = 'register' AND rru.link_id = {$id}
        GROUP BY DATE(dt)
) as d ON d.date = a.date
        left JOIN
      (
          SELECT count(*) cnt, DATE(dt) date
        FROM rev_referal_users rru
        WHERE rru.action = 'open' AND rru.link_id = {$id}
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

		$players_query = "SELECT p.id,p.user, p.level, COALESCE(SUM(pw.amount),0) as caps FROM players p
		INNER JOIN rev_referal_users rru ON rru.player_id=p.id AND rru.action='register'
		LEFT JOIN paymentwall pw ON pw.user_id=p.id 
		WHERE rru.link_id={$id} AND rru.dt between '".date('c', $searchForm->beginTS)."' and '".date('c', $searchForm->endTS)."'
		AND p.id IS NOT NULL
		GROUP BY p.id
		ORDER by p.id ASC";
		$players_result   = Yii::app()->db->commandBuilder->createSqlCommand($players_query)->queryAll();
		
		$playersReg = new CArrayDataProvider($players_result,array(
			'sort'=>array(
				'attributes'=>array(
					'id',
				)),
			'pagination'=>false,
		));
		$this->render( 'view', array(
			'searchForm' => $searchForm,
			'model'   => $model,
			'regData' => $regData,
			'regOpen' => $regOpen,
			'regReg'  => $regReg,
			'playersReg' => $playersReg
		) );
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel( $id ) {
		$model = ReferalLinks::model()->findByPk( $id );
		if ( $model === null ) {
			throw new CHttpException( 404, 'The requested page does not exist.' );
		}

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 *
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation( $model ) {
		if ( isset( $_POST['ajax'] ) && $_POST['ajax'] === 'referal-links-form' ) {
			echo CActiveForm::validate( $model );
			Yii::app()->end();
		}
	}
}
