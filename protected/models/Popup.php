<?php

/**
 * This is the model class for table "popup".
 *
 * The followings are the available columns in table 'popup':
 * @property integer $player_id
 * @property string $message
 * @property string $params
 * @property string $buttons
 * @property string $hash
 * @property string $action
 */
class Popup extends CActiveRecord
{
	const ACTION_SHOW_POPUP = 'showPopup';
	const ACTION_EXECUTE_JS = 'executeJS';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'popup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player_id, message, hash', 'required'),
			array('player_id', 'numerical', 'integerOnly'=>true),
			array('hash', 'length', 'max'=>32),
			array('action', 'length', 'max'=>9),
			array('params, buttons', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('player_id, message, params, buttons, hash', 'safe', 'on'=>'search'),
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
			'player_id' => 'id игрока',
			'message' => 'Строка под перевод',
			'params' => 'Параметры строки',
			'buttons' => 'Кнопки окна',
			'hash' => 'md5(message+params+buttons) для идентификации',
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

		$criteria->compare('player_id',$this->player_id);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('buttons',$this->buttons,true);
		$criteria->compare('hash',$this->hash,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Popup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function calcHash(){
		return md5($this->message.$this->params.$this->buttons);
	}


	public static function add($playerId, $message, $params = [], $action = self::ACTION_SHOW_POPUP,$buttons = null){
		if (!$params && $action == self::ACTION_EXECUTE_JS){
			$params = [
				'timestamp' => time()
			];
		}
		$model = new Popup();
		$model->player_id = $playerId;
		$model->message   = $message;
		$model->params    = CJSON::encode($params);
		$model->buttons   = CJSON::encode($buttons);
		$model->hash      = $model->calcHash();
		$model->action    = $action;
		$model->save();
	}

	public static function render(){
		$model = Popup::model()->findByAttributes(['player_id' => Yii::app()->stat->id]);
		if ($model){
			switch($model->action){
				case self::ACTION_EXECUTE_JS:
					echo("
<script type='text/javascript'>
  $(document).ready(function(){
    {$model->message}
  });
</script>
");
					break;
				case self::ACTION_SHOW_POPUP:
					// $message = $model->message;
					$message = t::encJs($model->message, CJSON::decode($model->params), t::ESCAPE_SINGLE);
					$buttons = array();
					if ($model->buttons) {
						$vars = CJSON::decode($model->buttons);
						if (is_array($vars)) {
							foreach ($vars as $btn_name=>$btn_val) {
								$buttons[t::get($btn_name)] = $btn_val;
							}
						}
					}
					$buttons = $buttons?$buttons:false;
					echo("
<script type='text/javascript'>
  $(document).ready(function(){
    $('body').on('rpl_subscribe', function(){
      $('body').trigger('popup', {
        title: '',
        text: '{$message}',
        buttons: ".CJSON::encode($buttons)." 
      });
    });
  });
</script>
");
					break;
			}
			Yii::app()->db->createCommand("DELETE FROM popup WHERE player_id = {$model->player_id} AND hash='{$model->hash}'")->execute();
		}
	}
}
