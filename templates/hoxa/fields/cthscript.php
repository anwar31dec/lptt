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

class JFormFieldCthScript extends JFormField
{
	
	protected $type = 'CthScript';

	
	protected function getInput()
	{
		$file = dirname(__FILE__).'/googlefontarray.php';

		$fontsArray = file_get_contents($file);

		$fontsArray = json_decode($fontsArray,false);

		//$fontsArrayVariants = array();

		$js = 'var fontVariants = new Array();';
		$js .= ' var fontsFamily = new Array();';

		foreach ($fontsArray->items as $font) {

			$js .= '    fontVariants[\''.str_replace(" ", "+", $font->family).'\'] = ["'.implode("\",\"", $font->variants).'"];';
			$js .= '    fontsFamily[\''.str_replace(" ", "+", $font->family).'\'] = "\''.$font->family.'\','.$font->category.';";';
		}

		$doc = JFactory::getDocument();

		$doc->addScriptDeclaration($js);


		JHtml::_('formbehavior.chosen', 'select');

		//$js = 'function changeFont(ele){ var fontCSS = jQuery(ele).find(\'option:selected\').val();var fontVariantsArray = fontVariants[fontCSS]; console.log(fontVariantsArray);}';

		//$doc->addScriptDeclaration($js);
		$doc->addScript(JURI::root(true).'/templates/'.Cthshortcodes::templateName().'/fields/assets/script.js');
		$doc->addStylesheet(JURI::root(true).'/templates/'.Cthshortcodes::templateName().'/fields/assets/style.css');
		//return '';
		//return $html;
		return;
	}

	
}
