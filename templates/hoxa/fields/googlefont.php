<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class JFormFieldGoogleFont extends JFormField
{
	
	protected $type = 'GoogleFont';

	
	protected function getInput()
	{
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		// Initialize JavaScript field attributes.
		//$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		$this->onchange = 'changeFont(this);';

		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true')
		{
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
		}
		else
		// Create a regular list.
		{
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		//JHtml::_('formbehavior.chosen', 'select');

		//$js = 'function changeFont(ele){ var fontCSS = jQuery(ele).find(\'option:selected\').val();var fontVariantsArray = fontVariants[fontCSS]; console.log(fontVariantsArray);}';

		//$doc = JFactory::getDocument();

		//$doc->addScriptDeclaration($js);
		//$doc->addScript(JURI::root(true).'/templates/'.Cthshortcodes::templateName().'/fields/assets/script.js');
		//$doc->addStylesheet(JURI::root(true).'/templates/'.Cthshortcodes::templateName().'/fields/assets/style.css');
		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$file = dirname(__FILE__).'/googlefontarray.php';

		$options = array();

		$fontsjson = file_get_contents($file);

		//echo'<pre>';var_dump(json_decode($fontsjson,false));die;

		//$file = dirname(__FILE__).'/googlefontarray.php';

		//$fontsSeraliazed = file_get_contents('http://phat-reaction.com/googlefonts.php?format=php');
 		//$fontArray = unserialize($fontsSeraliazed);

 		//file_put_contents($file, json_encode(unserialize($fontsSeraliazed)));

		//$fontsArray = unserialize($fontsSeraliazed);

		$fontsArray = json_decode($fontsjson,false);

		//echo'<pre>';var_dump($fontsArray->items);die;

		//$fontsArrayVariants = array();

		//$js = 'var fontVariants = new Array();';

		foreach ($fontsArray->items as $font) {
			$tmp = JHtml::_(
				'select.option', str_replace(" ", "+", $font->family), $font->family, 'value', 'text', false
			);

			// Set some option attributes.
			//$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			//$tmp->onclick = (string) $option['onclick'];

			//$tmp->onclick = 'alert(1);';
			//$fontsArrayVariants[str_replace(" ", "+", $font->family)] = $font->variants;

			//$js .= '    fontVariants[\''.str_replace(" ", "+", $font->family).'\'] = ["'.implode("\",\"", $font->variants).'"];';

			$options[] = $tmp;
		}


		//$doc = JFactory::getDocument();

		//$doc->addScriptDeclaration($js);
		

		reset($options);

		return $options;
	}
}
