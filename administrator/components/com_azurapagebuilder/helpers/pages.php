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

class PagesHelper extends JHelperContent {

	public static function addSubmenu($viewName = 'products'){
		JHtmlSidebar::addEntry(JText::_('COM_CARTS_SUBMENU_PRODUCTS'), 'index.php?option=com_carts&view=products', $viewName == 'products');

		JHtmlSidebar::addEntry(JText::_('COM_CARTS_SUBMENU_ORDERS'), 'index.php?option=com_carts&view=orders', $viewName == 'orders');
		
		JHtmlSidebar::addEntry(JText::_('COM_CARTS_SUBMENU_CATEGORIES'), 
			'index.php?option=com_categories&extension=com_carts', $viewName == 'categories');
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  $categoryId  The category ID.
	 * @param   integer  $id          The item ID.
	 * @param   string   $assetName   The asset name
	 *
	 * @return  JObject
	 *
	 * @since   3.1
	 */
	public static function getActions($component = '', $section = '', $id = 0)
	{
		// Reverted a change for version 2.5.6
		$user	= JFactory::getUser();
		$result	= new JObject;

		$path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';

		if ($section && $id)
		{
			$assetName = $component . '.' . $section . '.' . (int) $id;
		}
		else
		{
			$assetName = $component;
		}
		$actions = JAccess::getActions('com_azurapagebuilder', 'component');

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}

	

}