<?php
/**
 * @var \Hybrid_Auth $auth
 */


foreach (Hybrid_Auth::$config['providers'] as $name => $item){
	$name = strtolower($name);
	if($item['enabled'] && $name!='yahoo') {
	    echo(CHtml::link(CHtml::image("/img/social/$name.png", t::get('Войти через соц. сети').": ".$name." | Revival Online"), '/site/auth/provider/'.$name));
    }
}

