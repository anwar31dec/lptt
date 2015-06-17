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

class AzuraPagebuilderController extends JControllerLegacy
{
	protected $default_view = 'page';
	
	public function display($cachable = false, $urlparams = false)
	{
		//$cachable	= true;	// Huh? Why not just put that in the constructor?
		$cachable = true;
		$user		= JFactory::getUser();

		// Set the default view name and format from the Request.
		// Note we are using w_id to avoid collisions with the router and the return page.
		// Frontend is a bit messier than the backend.
		$id    = $this->input->getInt('id');
		$vName = $this->input->get('view', 'page');
		$this->input->set('view', $vName);

		if ($user->get('id') ||($this->input->getMethod() == 'POST' && $vName = 'page'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'id'				=> 'INT',
			'year' 				=> 'INT', 
			'month'             => 'INT',
			'limit'				=> 'UINT',
			'limitstart'		=> 'UINT',
			'filter_order'		=> 'CMD',
			'filter_order_Dir'	=> 'CMD',
			'lang'				=> 'CMD',
			'Itemid'            => 'INT'
		);

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_azurapagebuilder.edit.page', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		return parent::display($cachable, $safeurlparams);
	}
}
