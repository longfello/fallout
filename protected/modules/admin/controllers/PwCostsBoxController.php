<?php

class PwCostsBoxController extends Controller {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(
					'index', 'delete', 'create', 'update', 'load', 'addBox', 'addContent', 'deleteContent', 'updateContent',
					'addItem', 'updateItem', 'deleteItem'),
				'roles' => array('admin'),
			),
			array(
				'deny',  // deny all users
				'users' => array( '*' ),
			),
		);
	}

	public function actionCreate() {
		$model = new PwCostsBox;
		if ( isset( $_POST['PwCostsBox'] ) ) {
			$model->attributes = $_POST['PwCostsBox'];
			if ( $model->save() ) {
				$this->redirect( array( 'update', 'id' => $model->id ) );
			}
		}

		$this->render( 'create', array(
			'model' => $model,
		) );
	}
	public function actionIndex() {
		$model = new PwCostsBox( 'search' );
		$model->unsetAttributes();  // clear any default values
		if ( isset( $_GET['PwCostsBox'] ) ) {
			$model->attributes = $_GET['PwCostsBox'];
		}

		$this->render( 'index', array(
			'model' => $model,
		) );
	}
	public function actionLoad($id) {
		$model = new PwCostsBox( 'search' );
		$model->unsetAttributes();  // clear any default values
		$model->cost_id = $id;

		$this->renderPartial( 'load', array(
			'model' => $model,
			'id' => $id,
		) );
	}

	public function actionAddBox($id) {
		$model = new PwCostsBox;
		$model->cost_id = $id;

		if ( isset( $_POST['PwCostsBox'] ) ) {
			$model->attributes = $_POST['PwCostsBox'];
			if ( $model->save() ) {
				$this->redirect( array( 'update', 'id' => $model->id ) );
			}
		}


		$this->renderPopup( 'create', array(
			'model' => $model,
			'id'    => $id,
		), 'Добавить бонусный ящик', Popup::btnSave('Добавить'));
	}
	public function actionUpdate( $id ) {
		$model = $this->loadModel( $id );

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

		if ( isset( $_POST['PwCostsBox'] ) ) {
			$model->attributes = $_POST['PwCostsBox'];
			if ( $model->save() ) {
				if (Yii::app()->request->isAjaxRequest){
					Popup::close();
				} else {
					$this->redirect( array( 'index') );
				}
			}
		}

		if (Yii::app()->request->isAjaxRequest) {
			$this->renderPopup( 'update', array(
				'model' => $model,
			), 'Редактировать бонусный ящик', Popup::btnSave());
		} else {
			$this->render( 'update', array(
				'model' => $model,
			) );
		}
	}
	public function actionDelete( $id ) {
		$this->loadModel( $id )->delete();
		Popup::close();
		Yii::app()->end();
	}

	public function actionAddItem($id) {
		$model = new PwCostsContentItems();
		$model->content_id = $id;

		if ( isset( $_POST['PwCostsContentItems'] ) ) {
			$model->attributes = $_POST['PwCostsContentItems'];
			if ( $model->save() ) {
				$this->redirect( array( 'updateContent', 'id' => $model->content_id ) );
			}
		}

		$this->renderPopup( '_formItem', array(
			'model' => $model,
			'id'    => $id,
		), 'Добавить содержимое', '<div class="pull-left"><a class="btn btn-success esdPopup" href="/admin/PwCostsBox/update?id='.$model->content->box_id.'">Ящик</a><a class="btn btn-success esdPopup" href="/admin/PwCostsBox/updateContent?id='.$model->content_id.'">Содержимое</a></div>'.Popup::btnSave('Добавить'));
	}
	public function actionUpdateItem($id) {
		$model = PwCostsContentItems::model()->findByPk($id);
		if ( isset( $_POST['PwCostsContentItems'] ) ) {
			$model->attributes = $_POST['PwCostsContentItems'];
			if ( $model->save() ) {
				$this->redirect( array( 'updateContent', 'id' => $model->content_id ) );
			}
		}

		$this->renderPopup( '_formItem', array(
			'model' => $model,
			'id'    => $id,
		), 'Редактировать содержимое', '<div class="pull-left"><a class="btn btn-success esdPopup" href="/admin/PwCostsBox/update?id='.$model->content->box_id.'">Ящик</a><a class="btn btn-success esdPopup" href="/admin/PwCostsBox/updateContent?id='.$model->content_id.'">Содержимое</a></div>'.Popup::btnSave('Сохранить'));
	}
	public function actionDeleteItem($id) {
		$model = PwCostsContentItems::model()->findByPk($id);
		if ($model) {
			$box_id = $model->content->box_id;
			$model->delete();
			$this->actionUpdate($box_id);
		} else Popup::close();
	}

	public function actionAddContent($id) {
		$model = new PwCostsContent();
		$model->box_id = $id;

		if ( isset( $_POST['PwCostsContent'] ) ) {
			$model->attributes = $_POST['PwCostsContent'];
			if ( $model->save() ) {
				$this->redirect( array( 'updateContent', 'id' => $model->id ) );
			}
		}


		$this->renderPopup( 'createContent', array(
			'model' => $model,
			'id'    => $id,
		), 'Добавить содержимое бонусного ящика', '<div class="pull-left"><a class="btn btn-success esdPopup" href="/admin/PwCostsBox/update?id='.$model->box_id.'">Ящик</a></div>'.Popup::btnSave('Добавить'));
	}
	public function actionUpdateContent($id) {
		$model = PwCostsContent::model()->findByPk($id);

		if ( isset( $_POST['PwCostsContent'] ) ) {
			$model->attributes = $_POST['PwCostsContent'];
			if ( $model->save() ) {
				$this->redirect( array( 'update', 'id' => $model->box_id ) );
			}
		}


		$this->renderPopup( 'updateContent', array(
			'model' => $model,
			'id'    => $id,
		), 'Редактировать содержимое бонусного ящика', '<div class="pull-left"><a class="btn btn-success esdPopup" href="/admin/PwCostsBox/update?id='.$model->box_id.'">Ящик</a></div>'.Popup::btnSave('Сохранить'));
	}
	public function actionDeleteContent( $id ) {
		$model = PwCostsContent::model()->findByPk($id);
		$box_id = $model->box_id;
		if ($model) $model->delete();
		$this->redirect('/admin/PwCostsBox/update?id='.$box_id);
		Yii::app()->end();
	}

	public function loadModel( $id ) {
		$model = PwCostsBox::model()->findByPk( $id );
		if ( $model === null ) {
			throw new CHttpException( 404, 'The requested page does not exist.' );
		}

		return $model;
	}
	protected function performAjaxValidation( $model ) {
		if ( isset( $_POST['ajax'] ) && $_POST['ajax'] === 'pw-costs-box-form' ) {
			echo CActiveForm::validate( $model );
			Yii::app()->end();
		}
	}
}
