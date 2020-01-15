<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 13.09.16
 * Time: 11:54
 */
class referalUrlRule extends CBaseUrlRule {
	public $connectionID = 'db';
	const URL_PREFIX = 'r';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		if ($route==='referal/link')
		{
			if (isset($params['slug'])){
				$criteria = new CDbCriteria();
				$criteria->addCondition("slug = :slug");
				$criteria->params[':slug'] = $params['slug'];
				$model = ReferalLinks::model()->find($criteria);
				if ($model) {
					return self::URL_PREFIX.'/'.$model->slug;
				}
			}
		}
		return false;  // не применяем данное правило
	}

	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches))
		{
			if (isset($matches[3]) && $matches[1] == self::URL_PREFIX) {
				$criteria = new CDbCriteria();
				$criteria->addCondition("slug = :slug");
				$criteria->params[':slug'] = $matches[3];
				$model = ReferalLinks::model()->find($criteria);
				if ($model) {
					$_GET['model'] = $model;
					$_GET['slug']    = $model->slug;
					return 'referal/default/link';
				}
			}
			// Проверяем $matches[1] и $matches[3] на предмет
			// соответствия производителю и модели в БД.
			// Если соответствуют, выставляем $_GET['manufacturer'] и/или $_GET['model']
			// и возвращаем строку с маршрутом 'car/index'.
		}
		return false;  // не применяем данное правило
	}
}