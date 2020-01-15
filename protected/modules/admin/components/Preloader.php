<?php

class Preloader extends CWidget
{
	const SIZE_LARGE = 'large';
	const SIZE_MIDDLE = 'middle';
	const SIZE_SMALL = 'small';
	public $size = self::SIZE_MIDDLE;
	public $layout = 'default';

	/**
	 * @return string
	 */
	public function run()
	{
		return $this->render('Preloader/'.$this->layout, [
			'size' => $this->size,
		]);
	}
}