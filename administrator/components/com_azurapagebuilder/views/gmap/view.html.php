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

/**
 * HTML View class for the Media component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_media
 * @since       1.0
 */

class AzuraPagebuilderViewGMap extends JViewLegacy
{

	public function display($tpl = null)
	{
		$this->state = $this->get('state');

		parent::display($tpl);
	}
	
}
