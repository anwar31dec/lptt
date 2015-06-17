<?php
/**
* plg_easylanguage
* @author		isApp.it Team
* @copyright	Copyright (C) 2011 isApp.it. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @link			http://wwww.isapp.it
*/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('joomla.language.helper');

class plgSystemEasyLanguage extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	function onAfterRender()
	{
		if (!$this->params->def('allowBO', 0) && JFactory::getApplication()->isAdmin() ) return true;
	
		$buffer = JResponse::getBody();
	
		if ( strpos( $buffer, '{lang' ) === false ) return true;
	
		$regexTextarea = "#<textarea(.*?)>(.*?)<\/textarea>#is";
		$regexInput = "#<input(.*?)type=('|\")(.*?)('|\")(.*?)>#is";
		//$regexInput = "#<input(.*?)type=(.*?)(password|text|radio|checkbox)(.*?)>#is";
		$regexSelect = "#<option(.*?)>#is";
		
		$inputType = array('password', 'text', 'radio', 'checkbox', 'hidden');
	
		$matches = array();
		preg_match_all($regexTextarea, $buffer, $matches, PREG_SET_ORDER);
		$textarea = array();
		foreach ($matches as $key => $match) {
			if(strpos( $match[0], '{lang' ) !== false) {
				$textarea[$key] = $match[0];
				$buffer = str_replace($textarea[$key], '~^t'.$key.'~', $buffer);
			}
		}
	
		$matches = array();
		preg_match_all($regexInput, $buffer, $matches, PREG_SET_ORDER);
		$input = array();
		foreach ($matches as $key => $match) {
			if(!in_array($match[3], $inputType)) continue;
			if(strpos( $match[0], '{lang' ) !== false) {
				$input[$key] = $match[0];
				$buffer = str_replace($input[$key], '~^i'.$key.'~', $buffer);
			}
		}
		
		$matches = array();
		preg_match_all($regexSelect, $buffer, $matches, PREG_SET_ORDER);
		$select = array();
		foreach ($matches as $key => $match) {
			if(strpos( $match[0], '{lang' ) !== false) {
				$select[$key] = $match[0];
				$buffer = str_replace($select[$key], '~^s'.$key.'~', $buffer);
			}
		}
	
		if (strpos( $buffer, '{lang' ) !== false) {
			$buffer = self::filterText($buffer);
	
			if ($textarea) {
				foreach ($textarea as $key => $t) {
					$buffer = str_replace('~^t'.$key.'~', $t, $buffer);
				}
				unset($textarea);
			}
			if ($input) {
				foreach ($input as $key => $i) {
					$buffer = str_replace('~^i'.$key.'~', $i, $buffer);
				}
				unset($input);
			}
			if ($select) {
				foreach ($select as $key => $i) {
					$buffer = str_replace('~^s'.$key.'~', $i, $buffer);
				}
				unset($select);
			}
			JResponse::setBody($buffer);
		}
	
		unset($buffer);
	}
	
	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
	{
		$defaultLanguage = $this->params->def('defaultLanguage', 'default');
		$row->title = self::filterText($row->title, $defaultLanguage);
		$row->introtext = self::filterText($row->introtext, $defaultLanguage);
		if (property_exists($row, 'text')) {
			$row->text = self::filterText($row->text, $defaultLanguage);
		}
		return '';
	}
	
	static function getLagnCode() {
		$lang_codes = JLanguageHelper::getLanguages('lang_code');
		$lang_code 	= $lang_codes[JFactory::getLanguage()->getTag()]->sef;
		return $lang_code;
	}
	
	static function filterText($text, $default = false) {
		if ( strpos( $text, '{lang' ) === false ) return $text;
		$lang_code = self::getLagnCode();
		if ($default !== false && strpos( $text, '{lang '.$lang_code ) === false) {
			$lang_code = $default;
		}
		$regex = "#{lang ".$lang_code."}(.*?){\/lang}#is";
		$text = preg_replace($regex,'$1', $text);
		$regex = "#{lang [^}]+}.*?{\/lang}#is";
		$text = preg_replace($regex,'', $text);
		return $text;
	}
}