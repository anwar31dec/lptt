<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Platform
 * @subpackage	Form
 * @since		11.1
 */
class JFormFieldAzuraMedia extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	11.1
	 */
	protected $type = 'AzuraMedia';

	/**
	 * The initialised state of the document object.
	 *
	 * @var		boolean
	 * @since	11.1
	 */
	protected static $initialised = false;

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	11.1
	 */
	protected function getInput()
	{
		$assetField	= $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$authorField= $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
		$asset		= $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'] ;

		$link = (string) $this->element['link'];
		if (!self::$initialised) {

			// Load the modal behavior script.
			//JHtml::_('behavior.modal','a.modal_jform_azuramedia');

			// Build the script.
			$script = array();
			//$script[] = '	function jInsertFieldValue(value,id) {';
			//$script[] = '		var old_id = document.getElementById(id).value;';
			//$script[] = '		if (old_id != id) {';
			//$script[] = '			document.getElementById(id).value = value;';
			//$script[] = '		}';
			//$script[] = '	}';
            
            $script[] = 'function jInsertFieldValue(value, id) {';
        	$script[] = '	var $ = jQuery.noConflict();';
        	$script[] = '	var old_value = $("#" + id).val();';
        	$script[] = '	if (old_value != value) {';
        	$script[] = '		var $elem = $("#" + id);';
        	$script[] = '		$elem.val(value);';
        	$script[] = '		$elem.trigger("change");';
        	$script[] = '		if (typeof($elem.get(0).onchange) === "function") {';
        	$script[] = '			$elem.get(0).onchange();';
        	$script[] = '		}';
        			//jMediaRefreshPreview(id);
        	$script[] = '	}';
        	$script[] = '}';

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

			self::$initialised = true;
		}

		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// The text field.
		$html[] = '<div class="input-append">';
		$html[] = '	<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
					' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' .
					' '.$attr.' />';
		//$html[] = '</div>';

		$directory = (string)$this->element['directory'];
		if ($this->value && file_exists(JPATH_ROOT . '/' . $this->value)) {
			$folder = explode ('/',$this->value);
			array_shift($folder);
			array_pop($folder);
			$folder = implode('/',$folder);
		}
		elseif (file_exists(JPATH_ROOT . '/images/' . $directory)) {
			$folder = $directory;
		}
		else {
			$folder='';
		}
		// The button.
		//$html[] = '<div class="button2-left">';
		//$html[] = '	<div class="blank">';
		$html[] = '		<a class="btn modal_jform_azuramedia" title="'.JText::_('JSELECT').'"' .
					' href="'.($this->element['readonly'] ? '' : ($link ? $link : JURI::root(true).'/administrator/index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset='.$asset.'&amp;author='.$this->form->getValue($authorField)) . '&amp;fieldid='.$this->id.'&amp;folder='.$folder).'"' .
					' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
		$html[] = '			'.JText::_('JSELECT').'</a>';
		//$html[] = '	</div>';
		//$html[] = '</div>';

		//$html[] = '<div class="button2-left">';
		//$html[] = '	<div class="blank">';
		$html[] = '		<a class="btn" title="'.JText::_('JCLEAR').'"' .
					' href="#"'.
					' onclick="javascript:document.getElementById(\''.$this->id.'\').value=\'\';">';
        //$html[] = '			<i class="icon-remove"></i></a>';
		$html[] = '			'.JText::_('JCLEAR').'</a>';
		//$html[] = '	</div>';
		$html[] = '</div>';

		return implode("\n", $html);
	}
}