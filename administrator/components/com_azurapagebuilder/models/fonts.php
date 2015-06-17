<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class AzuraPagebuilderModelFonts extends JModelLegacy
{
	public function getState($property = null, $default = null)
	{
		static $set;

		if (!$set)
		{
			$input = JFactory::getApplication()->input;

			$fieldid = $input->get('fieldid', '');
			$this->setState('field.id', $fieldid);
			
			$set = true;
		}

		return parent::getState($property, $default);
	}
	
}