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

class JFormFieldAzuraGMapSelect extends JFormField
{

	protected $type = 'AzuraGMapSelect';

	protected static $initialised = false;

	protected function getInput()
	{
		
		$link = (string) $this->element['link'];
		if (!self::$initialised) {

			// Build the script.
			$script = array();
            
            $script[] = 'function jInsertLatLongValue(value, id) {';
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
			//JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

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


		$html[] = '<div >';
		//$html[] = '	<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
		//			' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"' .
		//			' '.$attr.' />';

		$html[] = '		<a class="btn btn-primary modal_jform_azuragmapselect" title="'.JText::_('JSELECT').'"' .
					' href="'.($this->element['readonly'] ? '' : ($link ? $link : JURI::root(true).'/administrator/index.php?option=com_azurapagebuilder&amp;view=gmap&amp;tmpl=component&amp;fieldid='.$this->id)).'"' .
					' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
		$html[] = '			'.JText::_('Change location').'</a>';

		$html[] = '</div>';

		return implode("\n", $html);
	}
}