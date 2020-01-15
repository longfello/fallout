<?php

return array(
	'title' => 'Выберите файл xml',

	'attributes' => array(
		'enctype' => 'multipart/form-data',
	),

	'elements' => array(
		'xml' => array(
			'type' => 'file',
		),
	),

	'buttons' => array(
		'reset' => array(
			'type' => 'reset',
			'label' => 'Сбросить',
		),
		'submit' => array(
			'type' => 'submit',
			'label' => 'Импортировать',
		),
	),
);