<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 29.03.16
 * Time: 15:03
 */
class KActiveForm extends TbActiveForm
{
    function autocompleteInput($model, $attribute, $chainModelName, $chainKey, $chainValue, $options = [], $limit = 1, $lang_id = false){
	    $this->initOptions($options);
	    $this->addCssClass($options['widgetOptions']['htmlOptions'], 'form-control real-attribute');
	    $fieldData = array(array($this, 'hiddenField'), array($model, $attribute, $options['widgetOptions']['htmlOptions']));

	    $chain = call_user_func($chainModelName.'::model');

	    $keys = array_map(function($a){ return trim($a); }, explode(',', $model->getAttribute($attribute)));

	    $chainModels = $chain->findAllByAttributes([$chainKey => $keys]);

		/** @var $chain CActiveRecord */
		/** @var $model CActiveRecord */

		$options['append'] = $this->render('KActiveForm/single', [
			'model'  => $model,
			'attribute'  => $attribute,
			'chainModelName' => $chainModelName,
			'chainModels' => $chainModels,
			'chainKey'   => $chainKey,
			'chainValue' => $chainValue,
			'limit'      => $limit,
			'lang_id'    => $lang_id
		], true);

	    return $this->customFieldGroupInternal($fieldData, $model, $attribute, $options);
    }

}