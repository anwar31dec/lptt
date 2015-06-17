<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Supports a modal article picker.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @since		1.6
 */
class JFormFieldLanguageSef extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'LanguageSef';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$languageSef = array();
		$lang_codes = JLanguageHelper::getLanguages('lang_code');
		$languageSef[] 	= JHTML::_('select.option',  '', '-'.JText::_('PLG_EASYLANGUAGE_SELECT_LANG').'-' );
		foreach($lang_codes as $lang) {
			$languageSef[] 	= JHTML::_('select.option',  $lang->sef , $lang->title );
		}
		
		return JHTML::_('select.genericlist', $languageSef, $this->name, '', 'value', 'text', $this->value );
	}
}
