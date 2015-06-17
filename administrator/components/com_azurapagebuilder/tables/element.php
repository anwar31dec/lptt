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

class AzuraPagebuilderTableElement extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__azurapagebuilder_elements', 'id', $db);
	}


	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id)
		{
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		}
		else
		{
			// New product. A product created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		// Set publish_up to null date if not set
		// if (!$this->publish_up)
		// {
		// 	$this->publish_up = $this->_db->getNullDate();
		// }

		// // Set publish_down to null date if not set
		// if (!$this->publish_down)
		// {
		// 	$this->publish_down = $this->_db->getNullDate();
		// }

		// Verify that the alias is unique
		//$table = JTable::getInstance('Page', 'AzuraPagebuilderTable');



		// if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
		// {
		// 	$this->setError(JText::_('COM_CARTS_ERROR_UNIQUE_ALIAS'));
		// 	return false;
		// }

		// Convert IDN urls to punycode
		//$this->url = JStringPunycode::urlToPunycode($this->url);

		return parent::store($updateNulls);
	}


}
