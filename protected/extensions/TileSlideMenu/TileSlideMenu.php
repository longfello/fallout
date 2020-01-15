<?php
/**
 * @name TileSlideMenu
 * @author Artur Oliveira - artur.oliveira@gmail.com - 2012-06-02
 * @version 1.0
 * @see http://arturoliveira.dyndns-web.com/yiidemo/tileSlideMenu/index
 * Based on http://valums.com/scroll-menu-jquery/
 *
 */
class TileSlideMenu extends CWidget
{
	/**
	 * @var string the tag name for the view container. Defaults to 'div'.
	 */
	public $tagName='div';
	/**
	 * @var array menu items configuration. Each array element represents the configuration
	 * for one particular item which can be either a string or an array.
	 *
	 * When an item is specified as a string, it should be in the format of "name:type:header",
	 * where "type" and "header" are optional. A {@link CDataColumn} instance will be created in this case,
	 * whose {@link CDataColumn::name}, {@link CDataColumn::type} and {@link CDataColumn::header}
	 * properties will be initialized accordingly.
	 *
	 * When an item is specified as an array, it will be used to create an item instance.
	 */
	public $items=array();
	/**
	 * @var boolean item visible
	 * By default all items are visible
	 */
	private $itemVisible = true;
	/**
	 * @var string the base script URL for all tile slide menu resources (eg javascript, CSS file, images).
	 * Defaults to null, meaning using the integrated tile slide menu resources (which are published as assets).
	 */
	public $baseScriptUrl;
	/**
	 * @var string the URL of the CSS file used by this tile slide menu. Defaults to null, meaning using the integrated
	 * CSS file. If this is set false, you are responsible to explicitly include the necessary CSS file in your page.
	 */
	public $cssFile;	
	/**
	 * @var array the HTML options for the view container tag.
	 */
	public $htmlOptions=array();
	/**
	 * @var string menu title
	 * Defaults to null, meaning it will not be displayed.
	 */
	public $menuTitle;
	/**
	 * @var string image height
	 * Defaults to 100
	 */
	public $imageHeight = 100;
	/**
	 * @var string image width
	 * Defaults to 100
	 */
	public $imageWidth = 100;
	/**
	 * Default image
	 */
	public $defaultImage;
	/**
	 * Default target for link/href
	 * Valid values are (source http://www.w3schools.com/tags/att_a_target.asp):
	 * _blank - Opens the linked document in a new window or tab
	 * _self - Opens the linked document in the same frame as it was clicked (this is default)
	 * _parent - Opens the linked document in the parent frame
	 * _top - Opens the linked document in the full body of the window
	 * framename - Opens the linked document in a named frame
	 * 
	 */
	public $defaultUrlTarget = "_self";
	/**
	 * Initializes the grid view.
	 * This method will initialize required property value.
	 */
 	public function init()
	{
		parent::init();

		$this->htmlOptions['class']='tile-slide-menu';

		if($this->baseScriptUrl===null)
			$this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.TileSlideMenu.assets'));

		if($this->cssFile!==false)
		{
			if($this->cssFile===null)
				$this->cssFile=$this->baseScriptUrl.'/TileSlideMenu.css';
			Yii::app()->getClientScript()->registerCssFile($this->cssFile);
		}
		if($this->defaultImage===null)
			$this->defaultImage=$this->baseScriptUrl . "/defaultImage.png";
		
		$this->htmlOptions['id']=$this->getId();
	}

	/**
	 * Renders the view.
	 * This is the main entry of the whole view rendering.
	 * Child classes should mainly override {@link renderContent} method.
	 */
	public function run()
	{
		$this->registerClientScript();
	
		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		$this->renderTitle();
		$this->renderItems();
		echo CHtml::closeTag($this->tagName);
	}
	
	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$id=$this->getId();
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->baseScriptUrl.'/TileSlideMenu.js',CClientScript::POS_END);				
		$cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').TileSlideMenu();");
	}
	
    protected function renderTitle()
    {
    	if ($this->menuTitle) {
    		echo CHtml::tag($this->tagName,array('class'=>'tile-slide-menu-title'),$this->menuTitle);
    	}
    }
    protected function renderItems()
    {
		$id=$this->getId();
    	echo CHtml::openTag($this->tagName,array('class'=>'tile-slide-menu-items','id'=>$id.'_items'));
    	echo CHtml::openTag('ul',array('class'=>'tile-slide-menu-item'));
    	foreach($this->items as $key=>$value)
 		{
 			if(!array_key_exists('visible',$value))
 				$value['visible'] = $this->itemVisible;
 			if($value['visible'])
 			{
 				if(!array_key_exists('imagePath',$value))
 				{
 					$value['imagePath'] = $this->defaultImage;
 				}
 				if(!array_key_exists('url',$value))
 				{
 					$value['url'] = '#';
 				}
 				if(!array_key_exists('urlTarget',$value))
 				{
 					$value['urlTarget'] = $this->defaultUrlTarget;
 				}
 				if(!array_key_exists('text',$value))
 				{
 					$value['text'] = '';
 				}
 				echo CHtml::openTag('li');
 				echo CHtml::openTag('a',array('href'=>$value['url'],'target'=>$value['urlTarget']));
 				echo CHtml::image($value['imagePath'],$value['text'],array('width'=>$this->imageWidth,'height'=>$this->imageHeight));
 				echo CHtml::tag('span',array(),$value['text']);
 				echo CHtml::closeTag('a');
 				echo CHtml::closeTag('li');
 			} 
 		}
 		echo CHtml::closeTag('ul');
 		echo CHtml::closeTag($this->tagName);
    }
}