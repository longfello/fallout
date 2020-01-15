<?php
/**
 *## TbCKEditor class file.
 *
 * @author Antonio Ramirez <antonio@clevertech.biz>
 * @copyright Copyright &copy; Clevertech 2012-
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

/**
 *## CKEditor 4 as a form input
 *
 * @see <http://docs.ckeditor.com/>
 *
 * @package booster.widgets.forms.inputs.wysiwyg
 */
class TbCKEditor extends CInputWidget
{
	/**
	 * @var TbActiveForm when created via TbActiveForm
	 * This attribute is set to the form that renders the widget
	 * @see TbActionForm->inputRow
	 */
	public $form;
	public $fileManager = false;

	/**
	 * @var array the CKEditor options
	 * @see <http://docs.cksource.com/>
	 * @since 10/30/12 10:40 AM the Editor used is CKEditor 4 Beta will be updated as final version is done
	 */
	public $editorOptions = array();

	public $uploadPath;

	/**
	 *### .run()
	 *
	 * Display editor
	 */
	public function run()
	{

		list($name, $id) = $this->resolveNameID();

		$this->registerClientScript($id);

		$this->htmlOptions['id'] = $id;

		// Do we have a model?
		if ($this->hasModel()) {
			if ($this->form) {
				$html = $this->form->textArea($this->model, $this->attribute, $this->htmlOptions);
			} else {
				$html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
			}
		} else {
			$html = CHtml::textArea($name, $this->value, $this->htmlOptions);
		}
		echo $html;
	}

	/**
	 *### .registerClientScript()
	 *
	 * Registers required javascript
	 *
	 * @param string $id
	 */
	public function registerClientScript($id)
	{
		Booster::getBooster()->cs->registerPackage('ckeditor');

		if ($this->fileManager) {
			Booster::getBooster()->cs->registerPackage('ckfinder');
			$this->editorOptions['filebrowserBrowseUrl'] = Booster::getBooster()->_assetsUrl.'/ckfinder/ckfinder.html';
			$this->editorOptions['filebrowserImageBrowseUrl'] = Booster::getBooster()->_assetsUrl.'/ckfinder/ckfinder.html?type=Images';
			$this->editorOptions['filebrowserFlashBrowseUrl'] = Booster::getBooster()->_assetsUrl.'/ckfinder/ckfinder.html?type=Flash';
			$this->editorOptions['filebrowserUploadUrl'] = Booster::getBooster()->_assetsUrl.'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
			$this->editorOptions['filebrowserImageUploadUrl'] = Booster::getBooster()->_assetsUrl.'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
			$this->editorOptions['filebrowserFlashUploadUrl'] = Booster::getBooster()->_assetsUrl.'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
		}

		$options = !empty($this->editorOptions) ? CJavaScript::encode($this->editorOptions) : '{}';

		if ($this->fileManager) {
			Yii::app()->clientScript->registerScript(
				__CLASS__ . '#' . $this->getId(),
				"var editor = CKEDITOR.replace( '$id', $options); CKFinder.setupCKEditor( editor, '../' );"
			);
		} else {
			Yii::app()->clientScript->registerScript(
				__CLASS__ . '#' . $this->getId(),
				"var editor = CKEDITOR.replace( '$id', $options);"
			);
		}
	}
}
