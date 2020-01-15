<?php

/**
 * This is the model class for table "cron_jobs".
 *
 * The followings are the available columns in table 'cron_jobs':
 * @property string $id
 * @property string $run_after
 * @property string $class_name
 * @property string $params
 */
class CronJobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cron_jobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('run_after, class_name, params', 'required'),
			array('run_after', 'length', 'max'=>255),
			array('class_name', 'length', 'max'=>255),
			array('params', 'length', 'max'=>1024),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, run_after, class_name, params', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'run_after' => 'Run After',
			'class_name' => 'Class Name',
			'params' => 'Params',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('run_after',$this->run_after,true);
		$criteria->compare('class_name',$this->class_name,true);
		$criteria->compare('params',$this->params,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CronJobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function run(){
		$models = CronJobs::model()->findAllBySql("SELECT * FROM cron_jobs WHERE run_after <= NOW()");
		foreach($models as $model){
			if(class_exists($model->class_name)){
				$worker = new $model->class_name($model->params);
				if (method_exists($worker, 'run')){
					echo('Running '.$worker->name.PHP_EOL);
					$worker->run();
					$model->delete();
				}
			}
		}
	}

	public static function findByParams($class_name, $params){
		$params = json_encode($params);
		$model = CronJobs::model()->findByAttributes([
			'class_name' => $class_name,
			'params'     => $params
		]);
		return $model;
	}
}
